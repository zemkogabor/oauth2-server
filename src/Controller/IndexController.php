<?php

declare(strict_types = 1);

namespace App\Controller;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class IndexController
{
    public function __construct(protected LoggerInterface $logger)
    {
    }

    public function actionIndex(): ResponseInterface
    {
        $response = new Response();

        $response->getBody()->write('OAuth 2.0 Server');

        return $response;
    }
}
