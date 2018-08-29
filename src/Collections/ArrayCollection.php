<?php

namespace FwsMailchimp\Collections;

use Iterator;
use Traversable;
use FwsMailchimp\Exception\IncorrectTypeException;
use FwsMailchimp\Entities\EntityInterface;

/**
 * Collection of mailchimp entities
 *
 * @author User
 */
class ArrayCollection implements Iterator
{

    /**
     * An array containing the entities of this collection.
     *
     * @var array
     */
    private $elements = array();

    /**
     *
     * @var integer
     */
    private $index = 0;

    /**
     * Initializes a new ArrayCollection.
     *
     * @param array|Traversable $elements
     * @throws IncorrectTypeException
     */
    public function __construct($elements = array())
    {
        if ($elements instanceof Traversable) {
            $elements = ArrayUtils::iteratorToArray($elements);
        }
        if (!is_array($elements)) {
            throw new IncorrectTypeException(sprintf('%s expects elements to ba an array or Traversable object, recuieved %s'), __METHOD__, is_object($elements) ? get_class($elements) : gettype($elements));
        }
        $this->elements = $elements;
    }

    /**
     *
     * @return array
     */
    public function toArray()
    {
        return $this->elements;
    }

    /**
     *
     * @return integer
     */
    public function key()
    {
        return $this->index;
    }

    /**
     *
     * @return void
     */
    public function next()
    {
        ++$this->index;
    }

    /**
     *
     * @return EntityInterface
     */
    public function current()
    {
        return $this->elements[$this->index];
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->index = 0;
    }

    /**
     *
     * @return boolean
     */
    public function valid()
    {
        return isset($this->elements[$this->index]);
    }

    /**
     *
     * @param string $key
     * @return EntityInterface|NULL
     */
    public function remove($key)
    {
        if (!isset($this->elements[$key]) && !array_key_exists($key, $this->elements)) {
            return NULL;
        }

        $removed = $this->elements[$key];
        unset($this->elements[$key]);

        return $removed;
    }

    /**
     *
     * @param EntityInterface $element
     * @return boolean
     */
    public function removeElement(EntityInterface $element)
    {
        $key = array_search($element, $this->elements, TRUE);

        if ($key === FALSE) {
            return FALSE;
        }

        unset($this->elements[$key]);

        return TRUE;
    }

    /**
     *
     * @return integer
     */
    public function count()
    {
        return count($this->elements);
    }

    /**
     *
     * @param integer $key
     * @param EntityInterface $value
     * @return \FwsMailchimp\Collections\ArrayCollection
     */
    public function set($key, EntityInterface $value)
    {
        $this->elements[$key] = $value;
        return $this;
    }

    /**
     *
     * @param EntityInterface $value
     * @return \FwsMailchimp\Collections\ArrayCollection
     */
    public function add(EntityInterface $value)
    {
        $this->elements[] = $value;
        return $this;
    }

    /**
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return empty($this->elements);
    }

    /**
     * Find entity by id
     * @param string $id
     * @return EntityInterface|NULL
     */
    public function get($id)
    {
        foreach ($this->elements as $entity) {
            if ($entity->getId() == $id) {
                return $entity;
            }
        }
        return NULL;
    }

}
