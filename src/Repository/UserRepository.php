<?php

declare(strict_types = 1);

namespace App\Repository;

use App\Entity\UserEntity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * @method UserEntity findOneBy(array $criteria, array $orderBy = null)
 */
class UserRepository extends EntityRepository implements UserRepositoryInterface
{
    public function __construct(protected LoggerInterface $logger, protected EntityManager $em)
    {
        parent::__construct($em, new ClassMetadata(UserEntity::class));
    }

    /**
     * {@inheritdoc}
     */
    public function getUserEntityByUserCredentials($username, $password, $grantType, ClientEntityInterface $clientEntity): ?UserEntity
    {
        $user = $this->findOneBy(['email' => $username]);

        if (!password_verify($password, $user->getPassword())) {
            return null;
        }

        return $user;
    }
}
