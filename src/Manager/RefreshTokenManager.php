<?php

declare(strict_types = 1);

namespace App\Manager;

use App\Entity\RefreshTokenEntity;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;

class RefreshTokenManager
{
    public function __construct(protected LoggerInterface $logger, protected EntityManager $em)
    {
    }

    public function clearExpired(): int
    {
        // todo: OR revoked
        $numOfClearedRefreshTokens = $this->em->createQueryBuilder()
            ->delete(RefreshTokenEntity::class, 'rt')
            ->where('rt.expiry_at < :expiry_at')
            ->setParameter('expiry_at', new DateTimeImmutable(), Types::DATETIME_IMMUTABLE)
            ->getQuery()
            ->execute();

        $this->logger->info(sprintf(
            'Cleared %d expired refresh token%s.',
            $numOfClearedRefreshTokens,
            $numOfClearedRefreshTokens === 1 ? '' : 's'
        ));

        return $numOfClearedRefreshTokens;
    }
}
