<?php

declare(strict_types = 1);

namespace App\Action;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class IndexAction implements RequestHandlerInterface
{
    public function __construct(protected LoggerInterface $logger)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $response = new Response();

        $response->getBody()->write('OAuth 2.0 Server');

        return $response;
    }
}
