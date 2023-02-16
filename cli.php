#!/usr/bin/env php
<?php

declare(strict_types = 1);

use App\Console\CreateClientCommand;
use App\Console\CreateUserCommand;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Application;

$container = require __DIR__ . '/config/bootstrap.php';

$cli = new Application('OAuth 2.0 Server console commands');

$cli->addCommands([
    new CreateClientCommand($container->get(EntityManager::class), 'create-client'),
    new CreateUserCommand($container->get(EntityManager::class), 'create-user'),
]);

$cli->run();
