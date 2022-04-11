<?php

namespace RSSReader\Command;

use RSSReader\Entity\Article;
use RSSReader\Repository\ArticleRepository;
use RSSReader\Repository\ResourceRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FetchResourcesCommand extends Command
{
    protected static $defaultName = 'rss:fetch';

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $resourceRepository = new ResourceRepository();
        $articleRepository = new ArticleRepository();

        $resources = $resourceRepository->getResources();

        foreach ($resources as $resource) {
            $output->writeln("Loading '{$resource->getName()}'");
            $xml = simplexml_load_file($resource->getUrl());
            $items = $xml->xpath( '//channel/item');

            foreach ($items as $item) {
                $article = new Article(
                    $articleRepository->getMaxIdArticle(),
                    $item->link,
                    $item->title,
                    strip_tags($item->description ?? ''),
                    $resource->getId(),
                    $item->pubDate
                );

                if (!$articleRepository->saveArticle($article)) {
                    $output->writeln("Article: {$article->getUrl()} already exist");
                } else {
                    $output->writeln("Article: {$article->getUrl()} saved");
                }
            }
        }

        return Command::SUCCESS;
    }
}