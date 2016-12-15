<?php

namespace Elephantly\KahlanBundle\Command;

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
            ->setDescription('Kahlan specs suite')
            ->addOption('config', null, InputOption::VALUE_REQUIRED, 'The PHP configuration file to use (default: \'kahlan-config.php\').')
            ->addOption('src', null, InputOption::VALUE_REQUIRED, 'Paths of source directories (default: [\'src\']).')
            ->addOption('spec', null, InputOption::VALUE_REQUIRED, 'Paths of specification directories (default: [\'spec\']).')
            ->addOption('pattern', null, InputOption::VALUE_REQUIRED, 'A shell wildcard pattern (default: \'*Spec.php\').
            ')
            ->addOption('reporter', 'r', InputOption::VALUE_REQUIRED, 'The name of the text reporter to use, the built-in text reporters
                                            are \'dot\', \'bar\', \'json\', \'tap\' & \'verbose\' (default: \'dot\').
                                            You can optionally redirect the reporter output to a file by using the
                                            colon syntax (multiple --reporter options are also supported).
            ')
            ->addOption('coverage', 'c', InputOption::VALUE_REQUIRED, 'Generate code coverage report. The value specify the level of
                                            detail for the code coverage report (0-4). If a namespace, class, or
                                            method definition is provided, it will generate a detailed code
                                            coverage of this specific scope (default `\'\'`).')
            ->addOption('clover', null, InputOption::VALUE_REQUIRED, 'Export code coverage report into a Clover XML format.')
            ->addOption('istanbul', null, InputOption::VALUE_REQUIRED, 'Export code coverage report into an istanbul compatible JSON format.')
            ->addOption('lcov', null, InputOption::VALUE_REQUIRED, 'Export code coverage report into a lcov compatible text format.
            ')
            ->addOption('ff', null, InputOption::VALUE_REQUIRED, 'Fast fail option. `0` mean unlimited (default: `0`).')
            ->addOption('no-colors', null, InputOption::VALUE_REQUIRED, 'To turn off colors. (default: `false`).')
            ->addOption('no-header', null, InputOption::VALUE_REQUIRED, 'To turn off header. (default: `false`).')
            ->addOption('include', null, InputOption::VALUE_REQUIRED, 'Paths to include for patching. (default: `[\'*\']`).')
            ->addOption('exclude', null, InputOption::VALUE_REQUIRED, 'Paths to exclude from patching. (default: `[]`).')
            ->addOption('persistent', null, InputOption::VALUE_REQUIRED, 'Cache patched files (default: `true`).')
            ->addOption('cache-clear', 'cc', InputOption::VALUE_REQUIRED, 'Cache patched files (default: `true`).
            ')
            ->addOption('autoclear', null, InputOption::VALUE_REQUIRED, 'Classes to autoclear after each spec (default: [
                                          `\'Kahlan\Plugin\Monkey\'`,
                                          `\'Kahlan\Plugin\Call\'`,
                                          `\'Kahlan\Plugin\Stub\'`,
                                          `\'Kahlan\Plugin\Quit\'`
                                      ])
            ')
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
