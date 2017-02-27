<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 19/02/17
 * Time: 22:00
 */

namespace Elephantly\KahlanBundle\Services;


/**
 * Class SpecBuilder
 * @package Elephantly\KahlanBundle\Services
 */
class SpecBuilder
{
    /**
     * @var ClassParser
     */
    private $parser;

    /**
     * SpecBuilder constructor.
     * @param ClassParser $parser
     */
    public function __construct(ClassParser $parser)
    {
        $this->parser = $parser;
    }
}