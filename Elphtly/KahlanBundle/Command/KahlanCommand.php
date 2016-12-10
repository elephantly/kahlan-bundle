<?php

namespace Elphtly\KahlanBundle\Command;

use Buzz\Browser;
use Kahlan\Filter\Filter;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Kahlan\Box\Box;
use Kahlan\Suite;
use Kahlan\Matcher;
use Kahlan\Cli\Kahlan;

class KahlanCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('kahlan:run')
            ->setDescription('Launch Kahlan specs suite')
            ->addOption('reporter', null, InputOption::VALUE_OPTIONAL, 'Defines the reporting style')
            ->addOption('config', null, InputOption::VALUE_OPTIONAL, 'Defines the custom config file')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container   = $this->getContainer();
        $client      = new Browser();
        $appDir      = $container->getParameter('kernel.root_dir');
        $autoloaders = [];

        $vendorDir   = 'vendor';

        if ($composerPath = realpath($appDir . '/../composer.json')) {
            $composerJson = json_decode(file_get_contents($composerPath), true);
            $vendorDir = isset($composerJson['vendor-dir']) ? $composerJson['vendor-dir'] : $vendorDir;
        }

        if ($relative = realpath($appDir . "/../{$vendorDir}/autoload.php")) {
            $autoloaders[] = include $relative;
        }

        if (!$absolute = realpath(__DIR__ . '/../../../autoload.php')) {
            $absolute = realpath(__DIR__ . '/../vendor/autoload.php');
        }

        if ($absolute && $relative !== $absolute) {
            $autoloaders[] = include $absolute;
        }

        if (!$autoloaders) {
            echo "\033[1;31mYou need to set up the project dependencies using the following commands: \033[0m" . PHP_EOL;
            echo 'curl -s http://getcomposer.org/installer | php' . PHP_EOL;
            echo 'php composer.phar install' . PHP_EOL;
            exit(1);
        }

        $box = box('kahlan', new Box());

        $box->service('suite.global', function() {
            return new Suite();
        });


        $specs = new Kahlan([
            'autoloader' => reset($autoloaders),
            'suite'      => $box->get('suite.global'),
            'container'  => $box->get('suite.container'),
            'client'     => $box->get('suite.client')
        ]);
        $specs->loadConfig($_SERVER['argv']);

        Filter::register('registering.container', function($chain) use ($specs, $container) {
            $root = $specs->suite();
            $root->container = $container;
            return $chain->next();
        });
        Filter::apply($this, 'run', 'registering.container');

        Filter::register('registering.client', function($chain) use ($specs, $client) {
            $root = $specs->suite();
            $root->container = $client;
            return $chain->next();
        });
        Filter::apply($this, 'run', 'registering.client');

        $specs->run();
        exit($specs->status());
    }
}
