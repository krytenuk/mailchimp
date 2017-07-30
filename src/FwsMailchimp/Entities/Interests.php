<?php

namespace FwsMailchimp\Entities;

use FwsMailchimp\Entities\InterestCategories as InterestCategoryEntity;

/**
 * Interests Entity
 *
 * @author Garry Childs (Freedom Web Services)
 */
class Interests implements EntityInterface
{

    /**
     *
     * @var string
     */
    private $id;

    /**
     *
     * @var string
     */
    private $categoryId;

    /**
     *
     * @var string
     */
    private $listId;

    /**
     *
     * @var string
     */
    private $name;

    /**
     *
     * @var integer
     */
    private $subscriberCount;

    /**
     *
     * @var integer
     */
    private $displayOrder;

    /**
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @return string
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     *
     * @return string
     */
    public function getListId()
    {
        return $this->listId;
    }

    /**
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     * @return integer
     */
    public function getSubscriberCount()
    {
        return $this->subscriberCount;
    }

    /**
     *
     * @return integer
     */
    public function getDisplayOrder()
    {
        return $this->displayOrder;
    }

    /**
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'name' => $this->name,
            'display_order' => $this->displayOrder,
        );
    }

}
