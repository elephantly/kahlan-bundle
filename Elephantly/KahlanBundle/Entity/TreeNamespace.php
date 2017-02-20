<?php

/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 20/02/17
 * Time: 13:55
 */
class TreeNamespace extends TreeObject
{
    public function __construct()
    {
        $this->type = self::TYPE_NS;
    }
}