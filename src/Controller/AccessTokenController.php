<?php

declare(strict_types = 1);

namespace App\Controller;

use GuzzleHttp\Psr7\Response;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class AccessTokenController
{
    public function __construct(protected LoggerInterface $logger, protected AuthorizationServer $authorizationServer)
    {
    }

    public function actionAccessToken(ServerRequestInterface $request): ResponseInterface
    {
        $response = new Response();

        try {
            // Try to respond to the access token request
            return $this->authorizationServer->respondToAccessTokenRequest($request, $response);
        } catch (OAuthServerException $exception) {
            // All instances of OAuthServerException can be converted to a PSR-7 response
            return $exception->generateHttpResponse($response);
        }
    }
}
