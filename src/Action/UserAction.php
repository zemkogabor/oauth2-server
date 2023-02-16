<?php

declare(strict_types = 1);

namespace App\Action;

use App\Repository\ScopeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class UserAction implements RequestHandlerInterface
{
    public function __construct(protected LoggerInterface $logger, protected EntityManager $em)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $userRepository = new UserRepository($this->logger, $this->em);
        $user = $userRepository->findOneBy(['id' => $request->getAttribute('oauth_user_id')]);

        $response = new Response(200, [
            'content-type' => 'application/json',
        ]);

        $params = [];

        if (in_array(ScopeRepository::SCOPE_BASIC, $request->getAttribute('oauth_scopes', []), true)) {
            $params = [
                'id' => $user->getIdentifier(),
            ];
        }

        if (in_array(ScopeRepository::SCOPE_EMAIL, $request->getAttribute('oauth_scopes', []), true)) {
            $params['email'] = $user->getEmail();
        }

        if (in_array(ScopeRepository::SCOPE_NAME, $request->getAttribute('oauth_scopes', []), true)) {
            $params['name'] = $user->getName();
        }

        $response->getBody()->write(json_encode($params));

        return $response;
    }
}
