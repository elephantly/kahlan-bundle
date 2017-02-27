<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 20/02/17
 * Time: 21:28
 */

namespace Elephantly\KahlanBundle\Entity;

interface NamespacedTreeObjectInterface
{
    /**
     * @return mixed
     */
    public function getFqcn();

    /**
     * @param $fqcn
     * @return mixed
     */
    public function setFqcn($fqcn);

    /**
     * @return bool
     */
    public function hasParent();

    /**
     * @return mixed
     */
    public function getParent();

    /**
     * @param TreeObjectInterface $parent
     */
    public function setParent(TreeObjectInterface $parent);
}