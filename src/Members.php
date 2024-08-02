<?php

namespace FwsMailchimp;

use FwsMailchimp\Client\Mailchimp;
use FwsMailchimp\Collections\ArrayCollection;
use FwsMailchimp\Entities\Members as MembersEntity;
use FwsMailchimp\Entities\InterestCategories as MemberInterestsCategory;
use FwsMailchimp\Exception\NoApiKeyException;
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
     * @param Mailchimp $client
     * @param array $config
     * @param Interests $interests
     * @throws NoApiKeyException
     */
    public function __construct(
        Mailchimp                  $client,
        array                      $config,
        private readonly Interests $interests
    )
    {
        parent::__construct($client, $config);

        $this->hydrator->addStrategy('timestampOpt', new DateTimeFormatterStrategy(DATE_ATOM));
        $this->hydrator->addStrategy('timestampSignup', new DateTimeFormatterStrategy(DATE_ATOM));
        $this->hydrator->addStrategy('lastChanged', new DateTimeFormatterStrategy(DATE_ATOM));
    }

    /**
     * Get lists members
     * @return ArrayCollection|null
     */
    public function listMembers(): ArrayCollection|null
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
    public function getMember(string $emailAddress): MembersEntity|null
    {
        if ($this->get($this->apiEndpoint . '/lists/' . $this->listId . '/members/' . $this->md5Hash($emailAddress)) === true) {
            $response = $this->getResponse();
            return $this->createMemberEntity($response);
        }
        return null;
    }

    /**
     * Add a new member to the list
     * To create a new member you must first get a blank member entity, new \FwsMailchimp\Entities\Members()
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
    public function subscribe(string $emailAddress): ?MembersEntity
    {
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
    public function unsubscribe(string $emailAddress): ?MembersEntity
    {
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
    public function archive(string $emailAddress): bool
    {
        return $this->delete($this->apiEndpoint . '/lists/' . $this->listId . '/members/' . $this->md5Hash($emailAddress));
    }
    
    /**
     * Soft delete list member (archive)
     * @param string $emailAddress
     * @return bool
     *@see Members::archive()
     */
    public function remove(string $emailAddress): bool
    {
        return $this->archive($emailAddress);
    }

    /**
     * Remove\Delete existing list member
     * WARNING: The member will be permanently deleted and can not resubscribe through the Mailchimp API
     * @param string $emailAddress
     * @return bool
     */
    public function removePermanent(string $emailAddress): bool
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
     * @param bool[] $interests
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
     * @param array<string, mixed> $memberArray
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
