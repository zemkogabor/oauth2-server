<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\RefreshTokenEntity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\Mapping\ClassMetadata;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * @method RefreshTokenEntity findOneBy(array $criteria, array $orderBy = null)
 */
class RefreshTokenRepository extends EntityRepository implements RefreshTokenRepositoryInterface
{
    public function __construct(protected LoggerInterface $logger, protected EntityManager $em)
    {
        parent::__construct($em, new ClassMetadata(RefreshTokenEntity::class));
    }

    /**
     * {@inheritdoc}
     * @throws ORMException
     */
    public function persistNewRefreshToken(RefreshTokenEntityInterface|RefreshTokenEntity $refreshTokenEntity): void
    {
        $refreshTokenEntity->setIsRevoke(false);
        $this->em->persist($refreshTokenEntity);
        $this->em->flush();
    }

    /**
     * {@inheritdoc}
     * @throws ORMException
     */
    public function revokeRefreshToken($tokenId): void
    {
        $refreshToken = $this->findOneBy(['token' => $tokenId]);
        $refreshToken->setIsRevoke(true);
        $this->em->persist($refreshToken);
        $this->em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function isRefreshTokenRevoked($tokenId): bool
    {
        $refreshToken = $this->findOneBy(['token' => $tokenId]);

        if ($refreshToken === null) {
            return true;
        }

        return $refreshToken->isRevoked();
    }

    /**
     * {@inheritdoc}
     */
    public function getNewRefreshToken(): RefreshTokenEntity
    {
        return new RefreshTokenEntity();
    }
}
