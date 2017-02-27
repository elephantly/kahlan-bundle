<?php

namespace Elephantly\KahlanBundle\Entity;

/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 20/02/17
 * Time: 13:55
 */
class Tree implements TreeObjectInterface
{
    const TYPE_TREE  = 0;
    const TYPE_NS    = 1;
    const TYPE_CLASS = 2;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $children = array();

    /**
     * Tree constructor.
     * @param string $name
     * @param array $data
     */
    public function __construct($name = '', array $data = array())
    {
        $this->name     = $name;
        $this->children = $data;
        $this->type     = self::TYPE_TREE;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return current($this->children);
    }

    /**
     * @return mixed
     */
    public function next()
    {
        return next($this->children);
    }

    /**
     * @return mixed
     */
    public function key()
    {
        return key($this->children);
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return false !== $this->current();
    }

    /**
     * @return mixed
     */
    public function rewind()
    {
        return reset($this->children);
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return !empty($this->children);
    }

    /**
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param TreeObjectInterface $child
     * @return bool
     */
    public function hasChild(TreeObjectInterface $child)
    {
        return false !== array_search($child, $this->children, true);
    }

    /**
     * @param TreeObjectInterface $child
     */
    public function addChild(TreeObjectInterface $child)
    {
        if (false === array_search($child, $this->children, true)) {
            $this->children[] = $child;
        }

        return $this;
    }

    /**
     * @param TreeObjectInterface $child
     * @return mixed
     */
    public function removeChild(TreeObjectInterface $child)
    {
        $key = array_search($child, $this->children, true);

        if (false === $key) {
            return false;
        }

        $removed = array_splice($this->children, $key, 1)[0];

        return $removed;
    }
}
