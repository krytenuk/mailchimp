<?php

namespace FwsMailchimp\Collections;

use Iterator;
use Laminas\Stdlib\ArrayUtils;
use Traversable;
use FwsMailchimp\Entities\EntityInterface;

/**
 * Collection of mailchimp entities
 *
 * @implements Iterator<int, EntityInterface>
 * @author User
 */
class ArrayCollection implements Iterator
{

    /**
     * An array containing the entities of this collection.
     *
     * @var EntityInterface[]
     */
    private array $elements = [];

    /**
     *
     * @var integer
     */
    private int $index = 0;

    /**
     * Initializes a new ArrayCollection.
     *
     * @param iterable|null $elements
     */
    public function __construct(iterable $elements = null)
    {
        if ($elements === null) {
            return;
        }

        if ($elements instanceof Traversable) {
            $elements = ArrayUtils::iteratorToArray($elements);
        }

        $this->elements = $elements;
    }

    /**
     *
     * @return array<int, EntityInterface>
     */
    public function toArray(): array
    {
        return $this->elements;
    }

    /**
     *
     * @return integer
     */
    public function key(): int
    {
        return $this->index;
    }

    /**
     *
     * @return void
     */
    public function next(): void
    {
        ++$this->index;
    }

    /**
     *
     * @return EntityInterface
     */
    public function current(): EntityInterface
    {
        return $this->elements[$this->index];
    }

    /**
     * @return void
     */
    public function rewind(): void
    {
        if ($this->isEmpty()) {
            $this->index = 0;
            return;
        }
        $this->index = min(array_keys($this->elements));
    }

    /**
     *
     * @return bool
     */
    public function valid(): bool
    {
        return isset($this->elements[$this->index]) && $this->elements[$this->index] instanceof EntityInterface;
    }

    /**
     *
     * @param int $key
     * @return bool
     */
    public function remove(int $key): bool
    {
        if (array_key_exists($key, $this->elements) === false) {
            return false;
        }

        unset($this->elements[$key]);
        return true;
    }

    /**
     *
     * @param EntityInterface $element
     * @return bool
     */
    public function removeElement(EntityInterface $element): bool
    {
        $key = array_search($element, $this->elements, true);

        if ($key === false) {
            return false;
        }

        unset($this->elements[$key]);
        return true;
    }

    /**
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->elements);
    }

    /**
     *
     * @param int $key
     * @param EntityInterface $value
     * @return ArrayCollection
     */
    public function set(int $key, EntityInterface $value): ArrayCollection
    {
        $this->elements[$key] = $value;
        return $this;
    }

    /**
     *
     * @param EntityInterface $value
     * @return ArrayCollection
     */
    public function add(EntityInterface $value): ArrayCollection
    {
        $this->elements[] = $value;
        return $this;
    }

    /**
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->elements);
    }

    /**
     * Find entity by id
     * @param string $id
     * @return EntityInterface|null
     */
    public function get(string $id): EntityInterface|null
    {
        if (empty($this->elements)) {
            return null;
        }
        foreach ($this->elements as $entity) {
            if ($entity->getId() === $id) {
                return $entity;
            }
        }
        return null;
    }

}
