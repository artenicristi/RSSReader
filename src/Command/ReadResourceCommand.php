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

        $index = 0;
        $cmd = null;
        do{
            if ($cmd === 'q'){
                break;
            }if ($cmd === '>'){
                $index++;
            }if ($cmd === '<'){
                $index--;
            }
            $output->writeln("<info>### {$articles[$index]->getTitle()}</info>");
            $output->writeln("{$articles[$index]->getContent()}");
        }while($cmd = $this->waitForInput());

        return Command::SUCCESS;
    }

    /**
     * @return false|string
     */
    protected function waitForInput(): string|false
    {
        $val = readline("(<>)");
        if (empty($val)){
            $val = '>';
        }
        return $val;
    }
}