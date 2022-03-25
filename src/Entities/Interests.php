<?php

namespace FwsMailchimp\Entities;

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
    private string $id = '';

    /**
     *
     * @var string
     */
    private string $categoryId = '';

    /**
     *
     * @var string
     */
    private string $listId = '';

    /**
     *
     * @var string
     */
    private string $name = '';

    /**
     *
     * @var int
     */
    private int $subscriberCount = 0;

    /**
     *
     * @var int
     */
    private int $displayOrder = 0;

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
    public function getCategoryId(): string
    {
        return $this->categoryId;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     *
     * @return int
     */
    public function getSubscriberCount(): int
    {
        return $this->subscriberCount;
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
     * @return array
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'display_order' => $this->displayOrder,
        ];
    }

}
