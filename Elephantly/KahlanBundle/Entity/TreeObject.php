<?php

/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 20/02/17
 * Time: 14:59
 */
abstract class TreeObject extends Tree
{
    /**
     * @var
     */
    protected $parent;

    /**
     * @var string
     */
    protected $fqcn;

    /**
     * TreeObject constructor.
     * @param array $name
     * @param TreeObjectInterface|null $parent
     * @param array $children
     */
    public function __construct($name, TreeObjectInterface $parent = null, $children = array())
    {
        parent::__construct($name, $children);

        $this->setFqcn($name);

        if (null !== $parent) {
            $this->parent = $parent;
            $parent->addChild($this);
            $this->setFqcn($parent->getFqcn().$name);
        }
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

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return TreeObject
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getFqcn()
    {
        return $this->fqcn;
    }

    /**
     * @param string $fqcn
     */
    public function setFqcn($fqcn)
    {
        if (self::TYPE_CLASS !== $this->type) {
            $fqcn .= '\\';
        }
        $this->fqcn .= $fqcn;

        return $this;
    }

}