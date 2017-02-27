<?php

namespace Elephantly\KahlanBundle\Entity;

/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 20/02/17
 * Time: 13:55
 */
class TreeClass extends TreeObject
{
    /**
     * @var array
     */
    protected $methods = array();

    /**
     * TreeClass constructor.
     * @param string $name
     * @param TreeObjectInterface|null $parent
     * @param array $children
     */
    public function __construct($name, TreeObjectInterface $parent = null, $children = array())
    {
        if (!($parent instanceof (Tree::class || TreeNamespace::class))) {
            throw new \InvalidArgumentException('TreeClasses can only have Trees or TreeNamespaces parents');
        }
        parent::__construct($name, $parent, $children);
        $this->type = self::TYPE_CLASS;

        $reflected  = new \ReflectionClass($this->getFqcn());
        $refMethods = $reflected->getMethods();
        foreach ($refMethods as $refMethod) {
            $this->methods[] = $refMethod->getName();
        }
    }
}