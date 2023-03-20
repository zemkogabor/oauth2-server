<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Repository\AccessTokenRepository;
use App\Repository\RefreshTokenRepository;
use App\Repository\ScopeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class UserController
{
    public function __construct(protected LoggerInterface $logger, protected EntityManager $em)
    {
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function actionActive(ServerRequestInterface $request): ResponseInterface
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

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws ORMException
     */
    public function actionLogout(ServerRequestInterface $request): ResponseInterface
    {
        // Revoke the access token of the current user.
        $accessTokenRepository = new AccessTokenRepository($this->logger, $this->em);
        $tokenId = $request->getAttribute('oauth_access_token_id');
        $accessTokenRepository->revokeAccessToken($tokenId);
        $accessToken = $accessTokenRepository->findOneBy(['token' => $tokenId]);
        $accessToken->setIsRevoke(true);

        // Related refresh tokens must also be deleted.
        $refreshTokenRepository = new RefreshTokenRepository($this->logger, $this->em);
        $refreshToken = $refreshTokenRepository->findOneBy(['accessToken' => $accessToken]);
        $refreshTokenRepository->revokeRefreshToken($refreshToken->getIdentifier());

        return new Response();
    }
}
