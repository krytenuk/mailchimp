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
    private $interestCategories;

    /**
     * Initialize the class
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
    public function refresh()
    {
        $this->clearErrors();
        $this->loadInterestCategories();
        if ($this->hasErrors()) {
            return FALSE;
        }
        return TRUE;
    }

    /**
     * List interest categories (groups) in list
     * @return ArrayCollection|NULL
     */
    public function listInterestCategories()
    {
        return $this->interestCategories;
    }

    /**
     * Get interest category (group)
     * @param type $interestCategoryId
     * @return InterestCategoryEntity|NULL
     */
    public function getInterestCategory($interestCategoryId)
    {
        if (!$this->interestCategories->isEmpty()) {
            foreach ($this->interestCategories as $category) {
                if ($category->getId() == $interestCategoryId) {
                    return $category;
                }
            }
        }
        return NULL;
    }

    /**
     * Find an interest category by interest id
     * @param string $interestId
     * @return InterestCategoryEntity|NULL
     */
    public function findInterestCategory($interestId)
    {

        if (!$this->interestCategories->isEmpty()) {
            foreach ($this->interestCategories as $category) {
                if (!$category->getInterests()->isEmpty()) {
                    foreach ($category->getInterests() as $interest) {
                        if ($interest->getId() == $interestId) {
                            return $category;
                        }
                    }
                }
            }
        }
        return NULL;
    }

    /**
     * Get category (group) interests
     * @param string $interestCategoryId
     * @return ArrayCollection|NULL
     */
    public function listCategoryInterests($interestCategoryId)
    {
        if (!$this->interestCategories->isEmpty()) {
            foreach ($this->interestCategories as $category) {
                if ($category->getId() == $interestCategoryId) {
                    return $category->getInterests();
                }
            }
        }
        return NULL;
    }

    /**
     *
     * @param string $interestId
     * @return InterestsEntity|NULL
     */
    public function getInterest($interestId)
    {

        if (!$this->interestCategories->isEmpty()) {
            foreach ($this->interestCategories as $category) {
                if (!$category->getInterests()->isEmpty()) {
                    foreach ($category->getInterests() as $interest) {
                        if ($interest->getId() == $interestId) {
                            return $interest;
                        }
                    }
                }
            }
        }
        return NULL;
    }

    /**
     * Load interest categories (groups)
     */
    private function loadInterestCategories()
    {
        if ($this->get($this->apiEndpoint . '/lists/' . $this->listId . '/interest-categories')) {
            $response = $this->getResponse();
            if (isset($response['categories']) && !empty($response['categories'])) {
                $hydrator = $this->getHydrator();
                foreach ($response['categories'] as $category) {
                    $entity = new InterestCategoryEntity();
                    $category['interests'] = $this->loadCategoryInterests($category['id']);
                    $hydrator->hydrate($category, $entity);
                    $this->interestCategories->add($entity);
                }
            }
        }
    }

    /**
     * Load category (group) interests
     * @param string $interestCategoryId
     * @return ArrayCollection
     */
    private function loadCategoryInterests($interestCategoryId)
    {
        $collection = new ArrayCollection();
        if ($this->get($this->apiEndpoint . '/lists/' . $this->listId . '/interest-categories/' . $interestCategoryId . '/interests')) {
            $response = $this->getResponse();
            if (isset($response['interests']) && !empty($response['interests'])) {
                $hydrator = $this->getHydrator();
                foreach ($response['interests'] as $interest) {
                    $entity = new InterestsEntity();
                    $hydrator->hydrate($interest, $entity);
                    $collection->add($entity);
                }
            }
        }
        return $collection;
    }

}
