<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 27/02/17
 * Time: 14:46
 */

namespace Elephantly\KahlanBundle\Services;

/**
 * Class TreeBuilder
 * @package Elephantly\KahlanBundle\Services
 */
use Elephantly\KahlanBundle\Entity\Tree;
use Elephantly\KahlanBundle\Entity\TreeClass;
use Elephantly\KahlanBundle\Entity\TreeNamespace;
use Elephantly\KahlanBundle\Entity\TreeObjectInterface;

/**
 * Class TreeBuilder
 * @package Elephantly\KahlanBundle\Services
 */
class TreeBuilder
{
    /**
     * @var ClassParser
     */
    private $parser;

    /**
     * @var SpecBuilder
     */
    private $builder;

    /**
     * @var string
     */
    private $root;

    /**
     * @var string
     */
    private $ext = '.spec.php';

    /**
     * TreeBuilder constructor.
     * @param ClassParser $parser
     * @param SpecBuilder $builder
     */
    function __construct(ClassParser $parser, SpecBuilder $builder, $root)
    {
        $this->parser  = $parser;
        $this->builder = $builder;
        $this->root    = $root.'/../';
    }

    /**
     * @param string $dir
     */
    public function buildSpecTree($dir = 'src')
    {
        $tree = $this->parser->createTree($dir);
        $this->createSpec($tree);
    }

    /**
     * @param TreeObjectInterface $object
     */
    public function createSpec(TreeObjectInterface $object)
    {
        foreach ($object->getChildren() as $child) {
            /* @var TreeObjectInterface $child */
            if ($child instanceof TreeNamespace || $object instanceof Tree) {
                $this->createDir($child);
            }
            if ($child instanceof TreeClass) {
                $this->createFile($child);
            }
            if ($child->hasChildren()) {
                $this->createSpec($child);
            }
        }
    }

    /**
     * @param TreeNamespace $namespace
     */
    public function createDir(TreeObjectInterface $namespace)
    {
        mkdir($this->root.$namespace->getFqcn(), 0755, true);
    }

    /**
     * @param TreeClass $class
     */
    public function createFile(TreeClass $class)
    {
        $filename = $this->root.$class->getFqcn().$this->ext;
        $content  = $this->builder->buildSpecsForClass($class);
        file_put_contents($filename, $content);
    }
}