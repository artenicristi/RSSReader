<?php

namespace RSSReader\Command;

use RSSReader\Repository\ResourceRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListResourcesCommand extends Command
{
    protected static $defaultName = 'rss:list';

    protected function configure(): void
    {
        $this->addArgument('name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $repo = new ResourceRepository();

        $output->writeln("<info>Resources:</info>");

        foreach ($repo->getResources() as $resource) {
            $output->writeln(" - <info>{$resource->getName()} </info> from {$resource->getUrl()}");
        }

        return Command::SUCCESS;
    }
}