<?php

/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 20/02/17
 * Time: 13:55
 */
class Tree implements RecursiveIterator
{
    /**
     * @var array
     */
    protected $children = array();

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

    public function addChild(TreeObjectInterface $child)
    {
        $this->children[] = $child;
    }

    public function removeChild(TreeObjectInterface $child)
    {
        $key     = array_search($child, $this->children);
        $removed = array_splice($this->children, $key, 1);

        return $removed[0];
    }

}