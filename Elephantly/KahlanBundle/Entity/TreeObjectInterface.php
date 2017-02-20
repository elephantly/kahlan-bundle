<?php

/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 20/02/17
 * Time: 13:56
 */
interface TreeObjectInterface
{
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
    public function hasParent();

    /**
     * @return mixed
     */
    public function getParent();
}