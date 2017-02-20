<?php

/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 20/02/17
 * Time: 13:55
 */
class TreeNamespace extends TreeObject
{
    /**
     * TreeNamespace constructor.
     * @param array $name
     * @param TreeObjectInterface|null $parent
     * @param array $children
     */
    public function __construct($name, TreeObjectInterface $parent = null, $children = array())
    {
        if (!($parent instanceof (Tree::class || TreeNamespace::class))) {
            throw new InvalidArgumentException('TreeNamespaces can only have Trees or TreeNamespaces parents');
        }
        parent::__construct($name, $parent, $children);
        $this->type = self::TYPE_NS;
    }
}