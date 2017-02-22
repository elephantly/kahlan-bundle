<?php

namespace Elephantly\KahlanBundle\Entity;

/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 20/02/17
 * Time: 14:59
 */
abstract class TreeObject extends Tree implements NamespacedTreeObjectInterface
{
    /**
     * @var TreeObjectInterface
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
    public function __construct($name, NamespacedTreeObjectInterface $parent = null, $children = array())
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