<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\AccessTokenEntity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\Mapping\ClassMetadata;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * @method AccessTokenEntity findOneBy(array $criteria, array $orderBy = null)
 */
class AccessTokenRepository extends EntityRepository implements AccessTokenRepositoryInterface
{
    public function __construct(protected LoggerInterface $logger, protected EntityManager $em)
    {
        parent::__construct($em, new ClassMetadata(AccessTokenEntity::class));
    }

    /**
     * {@inheritdoc}
     * @throws ORMException
     */
    public function persistNewAccessToken(AccessTokenEntityInterface|AccessTokenEntity $accessTokenEntity): void
    {
        $accessTokenEntity->setIsRevoke(false);
        $this->em->persist($accessTokenEntity);
        $this->em->flush();
    }

    /**
     * {@inheritdoc}
     * @throws ORMException
     */
    public function revokeAccessToken($tokenId): void
    {
        $accessToken = $this->findOneBy(['token' => $tokenId]);
        $accessToken->setIsRevoke(true);
        $this->em->persist($accessToken);
        $this->em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function isAccessTokenRevoked($tokenId): bool
    {
        $accessToken = $this->findOneBy(['token' => $tokenId]);

        if ($accessToken === null) {
            return true;
        }

        return $accessToken->isRevoked();
    }

    /**
     * {@inheritdoc}
     */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null): AccessTokenEntity|AccessTokenEntityInterface
    {
        $accessToken = new AccessTokenEntity();
        $accessToken->setClient($clientEntity);

        foreach ($scopes as $scope) {
            $accessToken->addScope($scope);
        }

        $accessToken->setUserIdentifier($userIdentifier);

        return $accessToken;
    }
}
