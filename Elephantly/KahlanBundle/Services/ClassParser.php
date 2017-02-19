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

    /**
     * @param string $dir
     */
    public function createTree($dir = '')
    {
        if (empty($this->map)) {
            if ('' == $dir) {
                throw new \MapNotFoundException('You must generate map before generating tree or give directory to this method.');
            }
            $this->createMap($dir);
        }

        $tree = array();

        $classes = array_keys($this->map);
        foreach ($classes as $class) {
            $methods      = get_class_methods($class);
            $functions    = array_fill(0, count($methods), 'method');
            $tree[$class] = array_combine($functions, $methods);
        }
    }
}