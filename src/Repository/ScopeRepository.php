<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\ScopeEntity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use Psr\Log\LoggerInterface;

class ScopeRepository implements ScopeRepositoryInterface
{
    public const SCOPE_BASIC = 'basic';
    public const SCOPE_EMAIL = 'email';
    public const SCOPE_NAME = 'name';

    public const SCOPES = [
        self::SCOPE_BASIC,
        self::SCOPE_EMAIL,
        self::SCOPE_NAME,
    ];

    public function __construct(protected LoggerInterface $logger)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getScopeEntityByIdentifier($identifier): ?ScopeEntity
    {
        if (!in_array($identifier, static::SCOPES)) {
            return null;
        }

        $scope = new ScopeEntity();
        $scope->setIdentifier($identifier);

        return $scope;
    }

    /**
     * {@inheritdoc}
     */
    public function finalizeScopes(array $scopes, $grantType, ClientEntityInterface $clientEntity, $userIdentifier = null): array
    {
        return $scopes;
    }
}
