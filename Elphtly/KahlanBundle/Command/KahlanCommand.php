<?php

namespace Elphtly\KahlanBundle\Command;

use Buzz\Browser;
use Kahlan\Filter\Filter;
use Symfony\Component\DependencyInjection\ContainerInterface;
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
            ->addOption('reporter', null, InputOption::VALUE_OPTIONAL)
            ->addOption('config', null, InputOption::VALUE_OPTIONAL)
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container   = $this->getContainer();

        $autoloaders = $this->registerAutoloaders($container);

        $specs = $this->createSpecs($autoloaders);

        $specs->loadConfig($_SERVER['argv']);

        $this->registerAdditionnalShortcuts($specs, $container);

        $specs->run();
        exit($specs->status());
    }

    /**
     * @param ContainerInterface $container
     * @return array
     */
    public function registerAutoloaders(ContainerInterface $container)
    {
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

        if (!$absolute = realpath(__DIR__ . '/../../../../app/autoload.php')) {
            $absolute = realpath(__DIR__ . '/../../../../vendor/autoload.php');
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

        return $autoloaders;
    }

    /**
     * @param array $autoloaders
     * @return Kahlan
     */
    public function createSpecs(array $autoloaders)
    {
        $box = box('kahlan', new Box());

        $box->service('suite.global', function() {
            return new Suite();
        });

        $specs = new Kahlan([
            'autoloader' => reset($autoloaders),
            'suite'      => $box->get('suite.global')
        ]);

        return $specs;
    }

    /**
     * @param $specs
     * @param $container
     * @param $client
     */
    public function registerAdditionnalShortcuts($specs, $container)
    {
        Filter::register('registering.container', function($chain) use ($specs, $container) {
            $root = $specs->suite();
            $root->container = $container;
            return $chain->next();
        });
        Filter::apply($specs, 'run', 'registering.container');

        $this->registerServicesShortcuts($specs, $container);

        $this->registerParameterShortcuts($specs, $container);

        $client = new Browser();
        Filter::register('registering.client', function($chain) use ($specs, $client) {
            $root = $specs->suite();
            $root->client = $client;
            return $chain->next();
        });
        Filter::apply($specs, 'run', 'registering.client');
    }

    /**
     * @param $specs
     * @param $container
     */
    public function registerServicesShortcuts($specs, $container)
    {
        Filter::register('registering.getService', function($chain) use ($specs, $container) {
            $root = $specs->suite();
            $root->get = function ($path) use ($container) {
                return $container->get($path);
            };
            return $chain->next();
        });
        Filter::apply($specs, 'run', 'registering.getService');

        Filter::register('registering.hasService', function($chain) use ($specs, $container) {
            $root = $specs->suite();
            $root->has = function ($path) use ($container) {
                return $container->has($path);
            };
            return $chain->next();
        });
        Filter::apply($specs, 'run', 'registering.hasService');
    }

    /**
     * @param $specs
     * @param $container
     */
    public function registerParameterShortcuts($specs, $container)
    {
        Filter::register('registering.getParameter', function($chain) use ($specs, $container) {
            $root = $specs->suite();
            $root->getParameter = function ($path) use ($container) {
                return $container->getParameter($path);
            };
            return $chain->next();
        });
        Filter::apply($specs, 'run', 'registering.getParameter');

        Filter::register('registering.hasParameter', function($chain) use ($specs, $container) {
            $root = $specs->suite();
            $root->hasParameter = function ($path) use ($container) {
                return $container->hasParameter($path);
            };
            return $chain->next();
        });
        Filter::apply($specs, 'run', 'registering.hasParameter');
    }
}
