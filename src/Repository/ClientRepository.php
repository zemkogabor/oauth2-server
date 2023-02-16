<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\ClientEntity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;

class ClientRepository extends EntityRepository implements ClientRepositoryInterface
{
    public function __construct(protected LoggerInterface $logger, protected EntityManager $em)
    {
        parent::__construct($em, new ClassMetadata(ClientEntity::class));
    }

    /**
     * {@inheritdoc}
     */
    public function getClientEntity($clientIdentifier): ?ClientEntity
    {
        return $this->findOneBy(['uuid' => $clientIdentifier]);
    }

    /**
     * {@inheritdoc}
     */
    public function validateClient($clientIdentifier, $clientSecret, $grantType): bool
    {
        if (!Uuid::isValid($clientIdentifier)) {
            return false;
        }

        $client = $this->findOneBy([
            'uuid' => $clientIdentifier,
            'secret' => $clientSecret,
        ]);

        if ($client !== null) {
            return true;
        }

        return false;
    }
}
