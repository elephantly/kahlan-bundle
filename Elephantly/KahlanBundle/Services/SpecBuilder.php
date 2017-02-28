<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 19/02/17
 * Time: 22:00
 */

namespace Elephantly\KahlanBundle\Services;

use Elephantly\KahlanBundle\Entity\TreeObjectInterface;
use Elephantly\KahlanBundle\Utils\Stubs;

/**
 * Class SpecBuilder
 * @package Elephantly\KahlanBundle\Services
 */
class SpecBuilder
{
    /**
     * @param TreeObjectInterface $object
     */
    public function buildSpecsForClass(TreeObjectInterface $object)
    {
        $reflection = new \ReflectionClass($object);
        $refMethods = $reflection->getMethods();
        $className = $reflection->getName();

        /* ----------- TODO: rework -----------*/
        // Preparing file content
        $classContent = Stubs::$classContext;

        // Initializing service to be tested stub
        $methodContext = Stubs::before('class', 'All')."\n";
        $this->replaceTokens($methodContext, array(
            'dependenciesBlock' => '%testedService%',
            'className'         => $className,
            ));

        // Parsing through class methods to generate stubs
        foreach ($refMethods as $method) {
            /* @var \ReflectionMethod $method */
            // Getting return type hint
            $type           = preg_match('/@return\s+([^\s]+)/', $method->getDocComment(), $matches) ? $matches[0] : 'int';

            // Getting method parameters
            $args           = array();
            foreach ($method->getParameters() as $param) {
                $args[] = $param->getName().', ';
            }
            $args           = implode(', ', $args);

            // Generating expect condition
            $expCond        = class_exists($type) ? 'AnInstanceOf' : 'A';
            $expect         = "toBe$expCond('%returnType%')";

            // Generating method stub
            $methodContext .= Stubs::$methodContext."\n";

            $params         = array(
                'methodSpecs'  => '%itContext%',
                'className'    => $className,
                'methodName'   => $method->getName(),
                'methodArgs'   => $args,
                'expectResult' => $expect,
                'returnType'   => $type,
            );

            $this->replaceTokens($methodContext, $params);
        }

        $this->replaceTokens($classContent, array(
            'className'    => $className,
            'classContent' => $methodContext,
            ));
    }

    /**
     * @param $string
     * @param array $params
     */
    public function replaceTokens(&$string, array $params = array())
    {
        foreach (array_merge(Stubs::$tokens, array_keys($params)) as $token) {
            switch (true) {
                case (isset(Stubs::$$token)):
                    $replacement = Stubs::$$token;
                    break;
                case (isset($params[$token])):
                    $replacement = $params[$token];
                    break;
                default:
                    $replacement = "%$token%";
                    break;
            }
            $string = str_replace("%$token%", $replacement, $string);
        }
    }
}