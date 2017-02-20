<?php

namespace Elephantly\KahlanBundle\Entity;
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 20/02/17
 * Time: 13:56
 */
interface TreeObjectInterface extends \RecursiveIterator
{
    /**
     * @return mixed
     */
    public function getName();

    /**
     * @return mixed
     */
    public function getType();

    /**
     * @return mixed
     */
    public function hasChildren();

    /**
     * @return mixed
     */
    public function getChildren();

    /**
     * @return mixed
     */
    public function hasChild(TreeObjectInterface $child);

    /**
     * @return mixed
     */
    public function addChild(TreeObjectInterface $child);

    /**
     * @return mixed
     */
    public function removeChild(TreeObjectInterface $child);
}