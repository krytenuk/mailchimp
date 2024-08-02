<?php

namespace FwsMailchimp;

use FwsMailchimp\Client\Mailchimp;
use FwsMailchimp\Collections\ArrayCollection;
use FwsMailchimp\Entities\EntityInterface;
use FwsMailchimp\Entities\Interests as InterestsEntity;
use FwsMailchimp\Entities\InterestCategories as InterestCategoryEntity;
use FwsMailchimp\Exception\NoApiKeyException;
use stdClass;

/**
 * Mailchimp interests functions
 *
 * @author Garry Childs (Freedom Web Services)
 */
class Interests extends AbstractMailchimp
{

    private ArrayCollection $interestCategories;

    /**
     * @param Mailchimp $client
     * @param array<int|string, mixed> $config
     * @throws NoApiKeyException
     */
    public function __construct(Mailchimp $client, array $config)
    {
        parent::__construct($client, $config);

        $this->interestCategories = new ArrayCollection();
        $this->loadInterestCategories();
    }

    /**
     * Refresh/reload the list
     * @return boolean
     */
    public function refresh(): bool
    {
        $this->clearErrors();
        $this->loadInterestCategories();
        if ($this->hasErrors()) {
            return false;
        }
        return true;
    }

    /**
     * List interest categories (groups) in list
     * @return ArrayCollection
     */
    public function listInterestCategories(): ArrayCollection
    {
        return $this->interestCategories;
    }

    /**
     * Get interest category (group)
     * @param string $interestCategoryId
     * @return EntityInterface|null
     */
    public function getInterestCategory(string $interestCategoryId): EntityInterface|null
    {
        if ($this->interestCategories->isEmpty()) {
            return null;
        }

        /** @var InterestCategoryEntity $category */
        foreach ($this->interestCategories as $category) {
            if ($category->getId() === $interestCategoryId) {
                return $category;
            }
        }

        return null;
    }

    /**
     * Find an interest category by interest id
     * @param string $interestId
     * @return InterestCategoryEntity|null
     */
    public function findInterestCategory(string $interestId): InterestCategoryEntity|null
    {
        if ($this->interestCategories->isEmpty()) {
            return null;
        }

        /** @var InterestCategoryEntity $category */
        foreach ($this->interestCategories as $category) {
            if ($category->getInterests()->isEmpty()) {
                continue;
            }
            foreach ($category->getInterests() as $interest) {
                if ($interest->getId() === $interestId) {
                    return $category;
                }
            }
        }

        return null;
    }

    /**
     * Get category (group) interests
     * @param string $interestCategoryId
     * @return ArrayCollection|null
     */
    public function listCategoryInterests(string $interestCategoryId): ArrayCollection|null
    {
        if ($this->interestCategories->isEmpty()) {
            return null;
        }

        /** @var InterestCategoryEntity $category */
        foreach ($this->interestCategories as $category) {
            if ($category->getId() === $interestCategoryId) {
                return $category->getInterests();
            }
        }

        return null;
    }

    /**
     *
     * @param string $interestId
     * @return InterestsEntity|null
     */
    public function getInterest(string $interestId): InterestsEntity|null
    {
        
        if ($this->interestCategories->isEmpty()) {
            return null;
        }

        /** @var InterestCategoryEntity $category */
        foreach ($this->interestCategories as $category) {
            if ($category->getInterests()->isEmpty()) {
                continue;
            }
            /** @var InterestsEntity $interest */
            foreach ($category->getInterests() as $interest) {
                if ($interest->getId() === $interestId) {
                    return $interest;
                }
            }
        }

        return null;
    }

    /**
     * Load interest categories (groups)
     */
    private function loadInterestCategories(): void
    {
        if ($this->get($this->apiEndpoint . '/lists/' . $this->listId . '/interest-categories') === false) {
            return;
        }

        $response = $this->getResponse();
        if (!is_array($response->categories) || empty($response->categories)) {
            return;
        }

        $hydrator = $this->getHydrator();
        foreach ($response->categories as $category) {
            $entity = new InterestCategoryEntity();
            $category->interests = $this->loadCategoryInterests($category->id);
            $hydrator->hydrate((array) $category, $entity);
            $this->interestCategories->add($entity);
        }
    }

    /**
     * Load category (group) interests
     * @param string $interestCategoryId
     * @return ArrayCollection
     */
    private function loadCategoryInterests(string $interestCategoryId): ArrayCollection
    {
        $collection = new ArrayCollection();
        if ($this->get($this->apiEndpoint . '/lists/' . $this->listId . '/interest-categories/' . $interestCategoryId . '/interests') === false) {
            return $collection;
        }

        $response = $this->getResponse();
        if (is_array($response->interests) || empty($response->interests)) {
            return $collection;
        }
        
        $hydrator = $this->getHydrator();
        /* @var stdClass $interest */
        foreach ($response->interests as $interest) {
            $entity = new InterestsEntity();
            $hydrator->hydrate((array) $interest, $entity);
            $collection->add($entity);
        }
        return $collection;
    }

}
