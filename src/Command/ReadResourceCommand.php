<?php

namespace RSSReader\Command;

use RSSReader\Repository\ArticleRepository;
use RSSReader\Repository\ResourceRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReadResourceCommand extends Command
{
    protected static $defaultName = 'rss:read';

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED);
        $this->addArgument('page', InputArgument::OPTIONAL, '', 1);
        $this->addArgument('limit', InputArgument::OPTIONAL, '', 10);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $resourceRepository = new ResourceRepository();
        $articleRepository = new ArticleRepository();

        $resource = $resourceRepository->getResourceByName($input->getArgument('name'));
        if (!$resource) {
            $output->writeln("There is no such resource");
            return Command::FAILURE;
        }

        $articles = $articleRepository->getArticlesByResource(
            $resource,
            $input->getArgument('page'),
            $input->getArgument('limit')
        );

        dump($articles);

        return Command::SUCCESS;
    }
}