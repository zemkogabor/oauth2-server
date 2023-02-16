<?php

declare(strict_types = 1);

use App\Action\AccessTokenAction;
use App\Action\IndexAction;
use App\Action\UserAction;
use App\Middleware\ResourceServerMiddleware;
use League\OAuth2\Server\ResourceServer;

use Slim\App;

return static function (App $app) {
    $app->get('/', IndexAction::class);
    $app->post('/access_token', AccessTokenAction::class);

    // Secured API
    $app->get('/user', UserAction::class)
        ->add(new ResourceServerMiddleware($app->getContainer()->get(ResourceServer::class)));
};
