<?php

namespace FwsMailchimp;

use FwsMailchimp\Client\Mailchimp;
use FwsMailchimp\Collections\ArrayCollection;
use FwsMailchimp\Entities\Interests as InterestsEntity;
use FwsMailchimp\Entities\InterestCategories as InterestCategoryEntity;

/**
 * Mailchimp interests functions
 *
 * @author Garry Childs (Freedom Web Services)
 */
class Interests extends AbstractMailchimp
{

    /**
     *
     * @var ArrayCollection
     */
    private ArrayCollection $interestCategories;

    /**
     * @param Mailchimp $client
     * @param array $config
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
     * @return ArrayCollection|null
     */
    public function listInterestCategories(): ?ArrayCollection
    {
        return $this->interestCategories;
    }

    /**
     * Get interest category (group)
     * @param string $interestCategoryId
     * @return InterestCategoryEntity|null
     */
    public function getInterestCategory(string $interestCategoryId): ?InterestCategoryEntity
    {
        if ($this->interestCategories->isEmpty()) {
            return null;
        }

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
    public function findInterestCategory(string $interestId): ?InterestCategoryEntity
    {
        if ($this->interestCategories->isEmpty()) {
            return null;
        }

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
    public function listCategoryInterests(string $interestCategoryId): ?ArrayCollection
    {
        if ($this->interestCategories->isEmpty()) {
            return null;
        }
        
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
    public function getInterest(string $interestId): ?InterestsEntity
    {
        
        if ($this->interestCategories->isEmpty()) {
            return null;
        }

        foreach ($this->interestCategories as $category) {
            if ($category->getInterests()->isEmpty()) {
                continue;
            }
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
        if (isset($response['categories']) === false || empty($response['categories']) === true) {
            return;
        }

        $hydrator = $this->getHydrator();
        foreach ($response['categories'] as $category) {
            $entity = new InterestCategoryEntity();
            $category['interests'] = $this->loadCategoryInterests($category['id']);
            $hydrator->hydrate($category, $entity);
            $this->interestCategories->add($entity);
        }
    }

    /**
     * Load category (group) interests
     * @param string $interestCategoryId
     * @return ArrayCollection
     */
    private function loadCategoryInterests($interestCategoryId): ArrayCollection
    {
        $collection = new ArrayCollection();
        if ($this->get($this->apiEndpoint . '/lists/' . $this->listId . '/interest-categories/' . $interestCategoryId . '/interests') === false) {
            return $collection;
        }

        $response = $this->getResponse();
        if (array_key_exists('interests', $response) === false || is_array($response['interests']) === false || empty($response['interests']) === true) {
            return $collection;
        }
        
        $hydrator = $this->getHydrator();
        foreach ($response['interests'] as $interest) {
            $entity = new InterestsEntity();
            $hydrator->hydrate($interest, $entity);
            $collection->add($entity);
        }
        return $collection;
    }

}
