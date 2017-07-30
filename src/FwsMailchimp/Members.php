<?php

namespace FwsMailchimp;

use FwsMailchimp\Client\Mailchimp;
use FwsMailchimp\Interests;
use FwsMailchimp\Collections\ArrayCollection;
use FwsMailchimp\Entities\Members as MembersEntity;
use Zend\Hydrator\Strategy\DateTimeFormatterStrategy;

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

    public function __construct(Mailchimp $client, Array $config, Interests $interests)
    {
        parent::__construct($client, $config);
        $this->interests = $interests;

        $this->hydrator->addStrategy('timestampOpt', new DateTimeFormatterStrategy(DATE_ATOM))
                ->addStrategy('timestampSignup', new DateTimeFormatterStrategy(DATE_ATOM))
                ->addStrategy('lastChanged', new DateTimeFormatterStrategy(DATE_ATOM));
    }

    /**
     * Get lists members
     * @return ArrayCollection|NULL
     */
    public function listMembers()
    {
        if ($this->get($this->apiEndpoint . '/lists/' . $this->listId . '/members')) {
            $response = $this->getResponse();
            if (isset($response['members']) && !empty($response['members'])) {
                $collection = new ArrayCollection();
                foreach ($response['members'] as $member) {
                    $collection->add($this->createMemberEntity($member));
                }
                return $collection;
            }
        }
        return NULL;
    }

    /**
     * Get list member
     * @param string $emailAddress
     * @return MembersEntity|NULL
     */
    public function getMember($emailAddress)
    {
        if ($this->get($this->apiEndpoint . '/lists/' . $this->listId . '/members/' . $this->md5Hash($emailAddress))) {
            $response = $this->getResponse();
            return $this->createMemberEntity($response);
        }
        return NULL;
    }

    /**
     * Add a new member to the list
     * To crfeste a new member you must first get a blank member entity, new \FwsMailchimp\Entities\Members()
     * @param MembersEntity $member
     * @return boolean
     */
    public function add(MembersEntity $member)
    {
        $this->setParameters($member->toArray());
        return $this->post($this->apiEndpoint . '/lists/' . $this->listId . '/members');
    }

    /**
     * Subscribe an existing list member
     * @param string $emailAddress
     * @return MembersEntity|FALSE
     */
    public function subscribe($emailAddress)
    {
        $this->status = 'subscribed';
        if ($this->patch($this->apiEndpoint . '/lists/' . $this->listId . '/members/' . $this->md5Hash($emailAddress))) {
            $response = $this->getResponse();
            return $this->createMemberEntity($response);
        }
        return FALSE;
    }

    /**
     * Unsubscribe an existing list member
     * @param string $emailAddress
     * @return MembersEntity|FALSE
     */
    public function unsubscribe($emailAddress)
    {
        $this->status = 'unsubscribed';
        if ($this->patch($this->apiEndpoint . '/lists/' . $this->listId . '/members/' . $this->md5Hash($emailAddress))) {
            $response = $this->getResponse();
            return $this->createMemberEntity($response);
        }
        return FALSE;
    }

    /**
     * Remove\Delete existing list member
     * @param string $emailAddress
     * @return boolean
     */
    public function remove($emailAddress)
    {
        return $this->delete($this->apiEndpoint . '/lists/' . $this->listId . '/members/' . $this->md5Hash($emailAddress));
    }

    /**
     * Update existing list members details
     * To update a member you must fist get the member entity with @see self::getMember()
     * @param MembersEntity $member
     * @return MembersEntity
     */
    public function update(MembersEntity $member)
    {
        if ($member->getId()) {
            $this->setParameters($member->toArray());
            if ($this->patch($this->apiEndpoint . '/lists/' . $this->listId . '/members/' . $this->md5Hash($member->getEmailAddress()))) {
                $response = $this->getResponse();
                return $this->createMemberEntity($response);
            }
        }
        return FALSE;
    }

    /**
     * Find interests for member
     * @param array $interests
     * @return ArrayCollection
     */
    private function findMemberInterests(Array $interests)
    {
        $interestCollection = new ArrayCollection();
        foreach ($interests as $interestId => $interested) {
            if ($interested) {
                if ($interestCategory = $this->interests->findInterestCategory($interestId)) {
                    if ($interestEntity = $this->interests->getInterest($interestId)) {
                        if ($category = $interestCollection->get($interestCategory->getId())) {
                            $category->addInterest($interestEntity);
                        } else {
                            $interestCategory = clone($interestCategory);
                            $interestCategory->addInterest($interestEntity);
                            $interestCollection->add($interestCategory);
                        }
                    }
                }
            }
        }
        return $interestCollection;
    }

    /**
     * Convert member response to a Members entity
     * @param array $memberArray
     * @return MembersEntity
     */
    private function createMemberEntity(Array &$memberArray)
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
