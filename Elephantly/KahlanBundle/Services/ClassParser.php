<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 19/02/17
 * Time: 22:00
 */

namespace Elephantly\KahlanBundle\Services;


use Elephantly\KahlanBundle\Entity\Tree;
use Elephantly\KahlanBundle\Entity\TreeNamespace;
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

        //--------- TEMPORARY ----------//
        $tree = new Tree();

        $classes = array_keys($this->map);
        foreach ($classes as $class) {
            $classParts = explode('\\', $class);
            $className = array_pop($classParts);
            $fqcn = array();

            do {
                $name      = array_shift($classParts);
                $namespace = new TreeNamespace($name);
                array_push($fqcn, $namespace);
            } while (!empty($classParts));

            array_push($fqcn, $className);

            for ($i = 0; $i < count($fqcn); $i++) {
                $fqcn[$i]->addChild($fqcn[$i+1]);
                $fqcn[$i+1]->setParent($fqcn[$i]);
            }
        }
    }
}