<?php

namespace FwsMailchimp\Entities;

use FwsMailchimp\Collections\ArrayCollection;
use FwsMailchimp\Entities\Interests as InterestsEntity;

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
    private string $id = '';

    /**
     *
     * @var string
     */
    private string $listId = '';

    /**
     *
     * @var string
     */
    private string $title = '';

    /**
     *
     * @var int
     */
    private int $displayOrder = 0;

    /**
     *
     * @var string
     */
    private string $type = '';

    /**
     *
     * @var ArrayCollection
     */
    private ArrayCollection $interests;

    /**
     * Initialize entity
     * @return void
     */
    public function __construct()
    {
        $this->interests = new ArrayCollection();
    }

    /**
     * Get all valid category types
     * @return array
     */
    public function getValidTypes(): array
    {
        return $this->validTypes;
    }

    /**
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     *
     * @return string
     */
    public function getListId(): string
    {
        return $this->listId;
    }

    /**
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     *
     * @return int
     */
    public function getDisplayOrder(): int
    {
        return $this->displayOrder;
    }

    /**
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     *
     * @return ArrayCollection
     */
    public function getInterests(): ArrayCollection
    {
        return $this->interests;
    }

    /**
     * 
     * @param InterestsEntity $interest
     * @return InterestCategories
     */
    public function addInterest(InterestsEntity $interest): InterestCategories
    {
        $this->interests->add($interest);
        return $this;
    }

    /**
     * Used for Mailchimp API
     * @return array
     */
    public function toArray(): array
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
