<?php

declare(strict_types = 1);

namespace App\Manager;

use App\Entity\AccessTokenEntity;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;

class AccessTokenManager
{
    public function __construct(protected LoggerInterface $logger, protected EntityManager $em)
    {
    }

    public function clearExpired(): int
    {
        $numOfClearedAccessTokens = $this->em->createQueryBuilder()
            ->delete(AccessTokenEntity::class, 'at')
            ->where('at.expiry_at < :expiry_at')
            ->setParameter('expiry_at', new DateTimeImmutable(), Types::DATETIME_IMMUTABLE)
            ->getQuery()
            ->execute();

        $this->logger->info(sprintf(
            'Cleared %d expired access token%s.',
            $numOfClearedAccessTokens,
            $numOfClearedAccessTokens === 1 ? '' : 's'
        ));

        return $numOfClearedAccessTokens;
    }
}
