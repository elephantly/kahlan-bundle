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
     * TreeBuilder constructor.
     * @param ClassParser $parser
     * @param SpecBuilder $builder
     */
    function __construct(ClassParser $parser, SpecBuilder $builder)
    {
        $this->parser  = $parser;
        $this->builder = $builder;
    }
}