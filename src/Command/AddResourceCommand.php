<?php

namespace RSSReader\Command;

use RSSReader\Entity\Resource;
use RSSReader\Repository\ResourceRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddResourceCommand extends Command
{
    protected static $defaultName = 'rss:add';

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED);
        $this->addArgument('url', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $resourceRepository = new ResourceRepository();

        $resource = new Resource(
            $resourceRepository->getMaxIdResource(),
            $input->getArgument('name'),
            $input->getArgument('url'),
            (string)simplexml_load_file($input->getArgument('url'))->children()->children()->lastBuildDate
        );

        if (!$resourceRepository->saveResource($resource)) {
            $output->writeln("Resource: <info>{$input->getArgument('name')}</info> already exist");
            return Command::FAILURE;
        }

        $output->writeln("Resource: <info>{$input->getArgument('name')}</info> adeed successfully");
        return Command::SUCCESS;
    }
}