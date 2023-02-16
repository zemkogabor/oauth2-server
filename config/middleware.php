<?php

declare(strict_types = 1);

use App\ErrorRender\JsonErrorRenderer;
use Psr\Log\LoggerInterface;
use Slim\App;

return static function (App $app) {
    // Routing
    $app->addRoutingMiddleware();

    // Body parsing
    $app->addBodyParsingMiddleware();

    // Error handler
    $isDev = $app->getContainer()->get('settings')['env'] === 'dev';
    $errorMiddleware = $app->addErrorMiddleware($isDev, true, true, $app->getContainer()->get(LoggerInterface::class));
    $errorHandler = $errorMiddleware->getDefaultErrorHandler();
    $errorHandler->registerErrorRenderer('application/json', JsonErrorRenderer::class);
    $errorHandler->forceContentType('application/json');
};
