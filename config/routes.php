<?php

declare(strict_types = 1);

use App\Controller\AccessTokenController;
use App\Controller\IndexController;
use App\Controller\UserController;
use App\Middleware\ResourceServerMiddleware;
use League\OAuth2\Server\ResourceServer;

use Slim\App;

return static function (App $app) {
    $app->get('/', [IndexController::class, 'actionIndex']);
    $app->post('/access_token', [AccessTokenController::class, 'actionAccessToken']);

    // Secured API
    $app->get('/user', [UserController::class, 'actionActive'])
        ->add(new ResourceServerMiddleware($app->getContainer()->get(ResourceServer::class)));
    $app->post('/user/logout', [UserController::class, 'actionLogout'])
        ->add(new ResourceServerMiddleware($app->getContainer()->get(ResourceServer::class)));
};
