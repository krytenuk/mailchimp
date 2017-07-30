<?php

namespace FwsMailchimp\Entities;

use FwsMailchimp\Collections\ArrayCollection;
/**
 * InterestCategories Entity
 *
 * @author User
 */
class InterestCategories implements EntityInterface
{

    /**
     *
     * @var array
     */
    private $validTypes = array(
        'checkboxes',
        'dropdown',
        'radio',
        'hidden',
    );

    /**
     *
     * @var string
     */
    private $id;

    /**
     *
     * @var string
     */
    private $listId;

    /**
     *
     * @var string
     */
    private $title;

    /**
     *
     * @var integer
     */
    private $displayOrder;

    /**
     *
     * @var string
     */
    private $type;

    /**
     *
     * @var ArrayCollection
     */
    private $interests;

    public function __construct()
    {
        $this->interests = new ArrayCollection();
    }

    /**
     * Get all valid category types
     * @return array
     */
    public function getValidTypes()
    {
        return $this->validTypes;
    }

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
    public function getListId()
    {
        return $this->listId;
    }

    /**
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
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
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     *
     * @return ArrayCollection
     */
    public function getInterests()
    {
        return $this->interests;
    }

    /**
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'title' => $this->title,
            'display_order' => $this->displayOrder,
            'type' => $this->type,
        );
    }

    public function __clone()
    {
        $this->interests = new ArrayCollection();
    }

}
