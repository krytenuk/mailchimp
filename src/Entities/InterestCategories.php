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
     * @var string[]
     */
    private array $validTypes = ['checkboxes', 'dropdown', 'radio', 'hidden'];

    private string $id = '';

    private string $listId = '';

    private string $title = '';

    private int $displayOrder = 0;

    private string $type = '';

    private ArrayCollection $interests;

    public function __construct()
    {
        $this->interests = new ArrayCollection();
    }

    /**
     * Get all valid category types
     * @return string[]
     */
    public function getValidTypes(): array
    {
        return $this->validTypes;
    }

    /**
     * Get interest category id
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Get list id
     * @return string
     */
    public function getListId(): string
    {
        return $this->listId;
    }

    /**
     * Get interest category title
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get interest category display order
     * @return int
     */
    public function getDisplayOrder(): int
    {
        return $this->displayOrder;
    }

    /**
     * Get interest category type
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Get interest category interests
     * @return ArrayCollection
     */
    public function getInterests(): ArrayCollection
    {
        return $this->interests;
    }

    /**
     *  Add an interest to the interest category
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
     * @return array{title: string, display_order: int, type: string}
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'display_order' => $this->displayOrder,
            'type' => $this->type
        ];
    }

    public function __clone()
    {
        $this->interests = new ArrayCollection();
    }

}
