<?php

namespace RSSReader\Command;

use ORM;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DeployCommand extends Command
{
    protected static $defaultName = 'rss:deploy';

    protected function configure(): void
    {
        $this->addOption('fresh', 'f', InputOption::VALUE_NEGATABLE, '', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($input->getOption('fresh')) {
            $output->writeln('<info>Drop old tables</info>');
            ORM::raw_execute("DROP TABLE IF EXISTS articles;");
            ORM::raw_execute("DROP TABLE IF EXISTS resources;");
        }

        ORM::raw_execute(
            "CREATE TABLE IF NOT EXISTS resources (
            id int PRIMARY KEY,
            name string UNIQUE NOT NULL,
            url string NOT NULL,
            created_at string NOT NULL
            );"
        );

        ORM::raw_execute(
            "CREATE TABLE IF NOT EXISTS articles (
            id int PRIMARY KEY,
            title string NOT NULL,
            url string UNIQUE NOT NULL,
            content string NOT NULL,
            created_at string NOT NULL,

            resource_id int NOT NULL 
            );"
        );

        $output->writeln('<info>Deployed</info>');

        return Command::SUCCESS;

    }
}