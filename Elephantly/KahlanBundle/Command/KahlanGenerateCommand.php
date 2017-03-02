<?php

namespace Elephantly\KahlanBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class KahlanGenerateCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('kahlan:generate')
            ->setDescription('Generates spec stubs')
            ->addOption('directory', 'd', InputOption::VALUE_REQUIRED, 'Specify root directory where to look for classes (default: [\'src\'])');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $treeBuilder = $this->getContainer()->get('kahlan.tree_builder');
        $dir = $input->getArgument('dir') ? : 'src' ;
        $treeBuilder->buildSpecTree($dir);
    }
}
