<?php

declare(strict_types = 1);

namespace App\Manager;

use App\Entity\AccessTokenEntity;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Psr\Log\LoggerInterface;

class AccessTokenManager
{
    public function __construct(protected LoggerInterface $logger, protected EntityManager $em)
    {
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function clearExpired(): int
    {
        $accessTokens = $this->em->createQueryBuilder()
            ->select('at', 'rt')
            ->from(AccessTokenEntity::class, 'at')
            ->leftJoin('at.refreshToken', 'rt')
            ->where('rt.id IS NULL')
            ->andWhere('at.expiry_at < :expiry_at')
            ->setParameter('expiry_at', new DateTimeImmutable(), Types::DATETIME_IMMUTABLE)
            ->getQuery()
            ->getResult();

        /**
         * @var AccessTokenEntity[] $accessTokens
         */
        foreach ($accessTokens as $accessToken) {
            $this->em->remove($accessToken);
        }
        $this->em->flush();

        $numOfClearedAccessTokens = count($accessTokens);

        $this->logger->info(sprintf(
            'Cleared %d expired access token%s.',
            $numOfClearedAccessTokens,
            $numOfClearedAccessTokens === 1 ? '' : 's'
        ));

        return $numOfClearedAccessTokens;
    }
}
