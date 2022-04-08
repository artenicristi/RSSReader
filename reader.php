<?php

use Symfony\Component\Console\Application;

const DB_PATH = __DIR__ . "/storage/rss.db";

require_once __DIR__ . "/vendor/autoload.php";

ORM::configure('sqlite:' . DB_PATH);

$application = new Application();
$application->add(new RSSReader\Command\AddResourceCommand());
$application->add(new RSSReader\Command\DeployCommand());
$application->add(new RSSReader\Command\ListResourcesCommand());
$application->add(new RSSReader\Command\ReadResourceCommand());
$application->add(new RSSReader\Command\FetchResourcesCommand());
$application->run();