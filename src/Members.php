<?php

namespace FwsMailchimp;

use FwsMailchimp\Client\Mailchimp;
use FwsMailchimp\Interests;
use FwsMailchimp\Collections\ArrayCollection;
use FwsMailchimp\Entities\Members as MembersEntity;
use FwsMailchimp\Entities\InterestCategories as MemberInterestsCategory;
use Laminas\Hydrator\Strategy\DateTimeFormatterStrategy;

/**
 * Mailchimp member functions
 *
 * @author Garry Childs (Freedom Web Services)
 */
class Members extends AbstractMailchimp
{

    /**
     *
     * @var Interests
     */
    private $interests;

    /**
     * 
     * @param Mailchimp $client
     * @param array $config
     * @param Interests $interests
     */
    public function __construct(Mailchimp $client, Array $config, Interests $interests)
    {
        parent::__construct($client, $config);
        $this->interests = $interests;

        $this->hydrator->addStrategy('timestampOpt', new DateTimeFormatterStrategy(DATE_ATOM));
        $this->hydrator->addStrategy('timestampSignup', new DateTimeFormatterStrategy(DATE_ATOM));
        $this->hydrator->addStrategy('lastChanged', new DateTimeFormatterStrategy(DATE_ATOM));
    }

    /**
     * Get lists members
     * @return ArrayCollection|null
     */
    public function listMembers(): ?ArrayCollection
    {
        if ($this->get($this->apiEndpoint . '/lists/' . $this->listId . '/members') === false) {
            return null;
        }

        $response = $this->getResponse();
        if (isset($response['members']) === false || empty($response['members']) === true) {
            return null;
        }

        $collection = new ArrayCollection();
        foreach ($response['members'] as $member) {
            $collection->add($this->createMemberEntity($member));
        }
        return $collection;
    }

    /**
     * Get list member
     * @param string $emailAddress
     * @return MembersEntity|null
     */
    public function getMember($emailAddress): ?MembersEntity
    {
        if ($this->get($this->apiEndpoint . '/lists/' . $this->listId . '/members/' . $this->md5Hash($emailAddress)) === true) {
            $response = $this->getResponse();
            return $this->createMemberEntity($response);
        }
        return null;
    }

    /**
     * Add a new member to the list
     * To crfeste a new member you must first get a blank member entity, new \FwsMailchimp\Entities\Members()
     * @param MembersEntity $member
     * @return boolean
     */
    public function add(MembersEntity $member): bool
    {
        $this->setParameters($member->toArray());
        return $this->post($this->apiEndpoint . '/lists/' . $this->listId . '/members');
    }

    /**
     * Subscribe an existing list member
     * @param string $emailAddress
     * @return MembersEntity|null
     */
    public function subscribe($emailAddress): ?MembersEntity
    {
        $this->status = 'subscribed';
        if ($this->patch($this->apiEndpoint . '/lists/' . $this->listId . '/members/' . $this->md5Hash($emailAddress))) {
            $response = $this->getResponse();
            return $this->createMemberEntity($response);
        }
        return null;
    }

    /**
     * Unsubscribe an existing list member
     * @param string $emailAddress
     * @return MembersEntity|null
     */
    public function unsubscribe($emailAddress): ?MembersEntity
    {
        $this->status = 'unsubscribed';
        if ($this->patch($this->apiEndpoint . '/lists/' . $this->listId . '/members/' . $this->md5Hash($emailAddress)) === true) {
            $response = $this->getResponse();
            return $this->createMemberEntity($response);
        }
        return null;
    }

    /**
     * Archive existing list member
     * @param string $emailAddress
     * @return bool
     */
    public function archive($emailAddress): bool
    {
        return $this->delete($this->apiEndpoint . '/lists/' . $this->listId . '/members/' . $this->md5Hash($emailAddress));
    }
    
    /**
     * Soft delete list member (archive)
     * @see Members::archive()
     * @param type $emailAddress
     * @return bool
     */
    public function remove($emailAddress): bool
    {
        return $this->archive($emailAddress);
    }

    /**
     * Remove\Delete existing list member
     * WARNING: The member will be permanently deleted and can not resubscribe through the Mailchimp API
     * @param string $emailAddress
     * @return bool
     */
    public function removePerminant($emailAddress): bool
    {
        return $this->post($this->apiEndpoint . '/lists/' . $this->listId . '/members/' . $this->md5Hash($emailAddress) . '/actions/delete-permanent');
    }

    /**
     * Update existing list members details
     * To update a member you must fist get the member entity with @see self::getMember()
     * @param MembersEntity $member
     * @return bool
     */
    public function update(MembersEntity $member): bool
    {
        if ($member->getId() === null) {
            return false;
        }
        
        $this->setParameters($member->toArray());
        if ($this->patch($this->apiEndpoint . '/lists/' . $this->listId . '/members/' . $this->md5Hash($member->getEmailAddress())) === false) {
            return false;
        }
        
        return true;
    }

    /**
     * Find interests for member
     * @param array $interests
     * @return ArrayCollection
     */
    private function findMemberInterests(array $interests): ArrayCollection
    {
        $interestCollection = new ArrayCollection();
        foreach ($interests as $interestId => $interested) {
            if ((bool)$interested === false) {
                continue;
            }
            
            $interestCategory = $this->interests->findInterestCategory($interestId);
            if ($interestCategory === null) {
                continue;
            }

            $interestEntity = $this->interests->getInterest($interestId);
            if ($interestEntity === null) {
                continue;
            }

            $category = $interestCollection->get($interestCategory->getId());
            if ($category instanceof MemberInterestsCategory) {
                $category->addInterest($interestEntity);
            } else {
                $interestCategory = clone($interestCategory);
                $interestCategory->addInterest($interestEntity);
                $interestCollection->add($interestCategory);
            }
        }
        return $interestCollection;
    }

    /**
     * Convert member response to a Members entity
     * @param array $memberArray
     * @return MembersEntity
     */
    private function createMemberEntity(array &$memberArray): MembersEntity
    {
        $hydrator = $this->getHydrator();
        $entity = new MembersEntity();

        $memberArray['interest_categories'] = $this->findMemberInterests($memberArray['interests']);

        $hydrator->hydrate($memberArray['location'], $entity->getLocation());
        unset($memberArray['location']);

        $hydrator->hydrate($memberArray['stats'], $entity->getStats());
        unset($memberArray['stats']);

        $entity->getLanguage()->setId($memberArray['language']);
        unset($memberArray['language']);

        $hydrator->hydrate($memberArray, $entity);

        return $entity;
    }

}
