<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 19/02/17
 * Time: 22:00
 */

namespace Elephantly\KahlanBundle\Services;


use Elephantly\KahlanBundle\Entity\Tree;
use Elephantly\KahlanBundle\Entity\TreeClass;
use Elephantly\KahlanBundle\Entity\TreeNamespace;
use Elephantly\KahlanBundle\Entity\TreeObjectInterface;
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
        $tree = new Tree($dir);
        // Filtering only FQCNs
        $classes = array_keys($this->map);
        // Parsing FQCNs
        foreach ($classes as &$class) {
            $class     = explode('\\', $class);
            for ($i = count($class) -1; $i > 0; $i--) {
                $j = $i-1;
                $class[$j] = array($class[$j] => $class[$i]);
                unset($class[$i]);
            }
        }

        for ($i = 1; $i < count($classes); $i++) {
            $this->compare($classes[0], $classes[$i]);
        }
        $map = $classes[0][0];

        // Defining relationships
        $this->setParentAndChildren($map, $tree);

        return $tree;
    }

    public function compare(&$var1, $var2) {
        if (is_array($var1) && is_array($var2)) {
            foreach ($var2 as $key => $row) {
                if (array_key_exists($key, $var1)) {
                    $this->compare($var1[$key], $var2[$key]);
                } else {
                    foreach ($var2 as $subkey => $value) {
                        $var1[$subkey] = $value;
                        unset($var2[$subkey]);
                    }
                }
            }
        } else {
            if (is_array($var1)) {
                $var1 = array_merge($var1, array($var2));
            } else if (is_array($var2)) {
                $var1 = array_merge(array($var1), $var2);
            } else {
                $var1 = array($var1, $var2);
            }
        }
        unset($var2);
    }

    public function setParentAndChildren(array &$array, &$parent)
    {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $this->setParentAndChildren($value, $array);
            }
            if (is_string($key)) {
                $key = new TreeNamespace($key);
            }
            if (is_string($value)) {
                $value = new TreeClass($value);
            }
            $key->setParent($parent);
            $parent->addChild($key);
        }
    }
}