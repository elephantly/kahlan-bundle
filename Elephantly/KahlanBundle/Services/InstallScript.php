<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 22/03/17
 * Time: 21:08
 */

namespace Elephantly\KahlanBundle\Services;

use Sensio\Bundle\GeneratorBundle\Manipulator\KernelManipulator;

class InstallScript
{
    public static function initBundle()
    {
        $kernel = new \AppKernel('prod', false);
        $kernel->loadClassCache();

        $kernelManipulator = new KernelManipulator($kernel);

        $ret = $kernelManipulator->addBundle('Elephantly\\KahlanBundle\\KahlanBundle');
    }
}
