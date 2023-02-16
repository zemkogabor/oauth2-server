<?php

declare(strict_types = 1);

use Slim\Factory\AppFactory;

$container = require __DIR__ . '/../config/bootstrap.php';

AppFactory::setContainer($container);
$app = AppFactory::create();

// Register middleware
(require __DIR__ . '/../config/middleware.php')($app);

// Register routes
(require __DIR__ . '/../config/routes.php')($app);

$app->run();
