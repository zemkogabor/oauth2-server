#!/usr/bin/env php
<?php

declare(strict_types = 1);

use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\ORM\EntityManager;
use Doctrine\Migrations\Tools\Console\Command;
use Symfony\Component\Console\Application;

$container = require __DIR__ . '/config/bootstrap.php';

$config = new PhpFile('config/migrations.php');

$dependencyFactory = DependencyFactory::fromEntityManager($config, new ExistingEntityManager($container->get(EntityManager::class)));

// https://www.doctrine-project.org/projects/doctrine-migrations/en/3.5/reference/custom-integration.html#custom-integration
$cli = new Application('Doctrine Migrations');

$cli->addCommands([
    new Command\DumpSchemaCommand($dependencyFactory),
    new Command\ExecuteCommand($dependencyFactory),
    new Command\GenerateCommand($dependencyFactory),
    new Command\LatestCommand($dependencyFactory),
    new Command\ListCommand($dependencyFactory),
    new Command\MigrateCommand($dependencyFactory),
    new Command\RollupCommand($dependencyFactory),
    new Command\StatusCommand($dependencyFactory),
    new Command\SyncMetadataCommand($dependencyFactory),
    new Command\VersionCommand($dependencyFactory),
    new Command\DiffCommand($dependencyFactory),
]);

$cli->run();
