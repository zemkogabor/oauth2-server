<?php

declare(strict_types = 1);

use App\Repository\AccessTokenRepository;
use App\Repository\ClientRepository;
use App\Repository\RefreshTokenRepository;
use App\Repository\ScopeRepository;
use App\Repository\UserRepository;
use DI\ContainerBuilder;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use League\OAuth2\Server\ResourceServer;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

return static function (ContainerBuilder $containerBuilder, array $settings, LoggerInterface $logger) {
    $containerBuilder->addDefinitions([
        'settings' => $settings,
        LoggerInterface::class => $logger,
        EntityManager::class => function (ContainerInterface $container) {
            $settingsDoctrine = $container->get('settings')['doctrine'];
            $cache = $settingsDoctrine['dev_mode'] ?
                new ArrayAdapter() :
                new FilesystemAdapter(directory: $settingsDoctrine['cache_dir']);

            $config = ORMSetup::createAttributeMetadataConfiguration(
                $settingsDoctrine['metadata_dirs'],
                $settingsDoctrine['dev_mode'],
                $settingsDoctrine['proxy_dir'],
                $cache
            );

            foreach ($settingsDoctrine['types'] as $dbType => $doctrineType) {
                Type::addType($dbType, $doctrineType);
            }

            return new EntityManager(DriverManager::getConnection($settingsDoctrine['connection']), $config);
        },
        AuthorizationServer::class => function (ContainerInterface $container) {
            $settings = $container->get('settings');
            $logger = $container->get(LoggerInterface::class);
            $em = $container->get(EntityManager::class);

            // Set up the authorization server
            $server = new AuthorizationServer(
                new ClientRepository($logger, $em),
                new AccessTokenRepository($logger, $em),
                new ScopeRepository($logger),
                'file://' . __DIR__ . '/../var/keys/private.key',
                $settings['encryption_key']
            );

            $refreshTokenRepository = new RefreshTokenRepository($logger, $em);

            $passwordGrant = new PasswordGrant(
                new UserRepository($logger, $em),
                $refreshTokenRepository,
            );

            $passwordGrant->setRefreshTokenTTL(new DateInterval('P1M')); // refresh tokens will expire after 1 month

            // Enable the password grant on the server with a token TTL of 5 minute
            $server->enableGrantType(
                $passwordGrant,
                new DateInterval('PT5M'),
            );

            $refreshTokenGrant = new RefreshTokenGrant($refreshTokenRepository);
            $refreshTokenGrant->setRefreshTokenTTL(new DateInterval('P1M')); // The refresh token will expire in 1 month

            $server->enableGrantType(
                $refreshTokenGrant,
                new \DateInterval('PT5M') // The new access token will expire after 5 minute
            );

            return $server;
        },
        ResourceServer::class => function (ContainerInterface $container) {
            return new ResourceServer(
                new AccessTokenRepository($container->get(LoggerInterface::class), $container->get(EntityManager::class)),
                'file://' . __DIR__ . '/../var/keys/public.key'
            );
        },
    ]);
};
