<?php

/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 20/02/17
 * Time: 14:59
 */
abstract class TreeObject implements TreeObjectInterface
{
    const TYPE_NS    = 1;
    const TYPE_CLASS = 2;

    /**
     * @var
     */
    protected $type;

    /**
     * @var
     */
    protected $children = array();

    /**
     * @var
     */
    protected $parent;

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return !empty($this->children);
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return bool
     */
    public function hasParent()
    {
        return !empty($this->parent);
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param TreeObjectInterface $parent
     */
    public function setParent(TreeObjectInterface $parent)
    {
        $this->parent = $parent;
    }

}