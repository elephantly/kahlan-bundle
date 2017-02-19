<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 19/02/17
 * Time: 22:00
 */

namespace Elephantly\KahlanBundle\Services;


use Symfony\Component\ClassLoader\ClassMapGenerator;

/**
 * Class ClassParser
 * @package Elephantly\KahlanBundle\Services
 */
class ClassParser
{
    /**
     * @var
     */
    private $map;

    /**
     * @var
     */
    private $tree;

    /**
     * @return mixed
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * @return mixed
     */
    public function getTree()
    {
        return $this->tree;
    }

    /**
     * @param $dir
     */
    public function createMap($dir)
    {
        $this->map = ClassMapGenerator::createMap($dir);
    }

    public function createTree()
    {
        if (empty($this->map)) {
            throw new \MapNotFoundException('You must generate map before generating tree');
        }
        $classes = array_keys($this->map);
    }
}