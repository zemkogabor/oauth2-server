<?php

declare(strict_types = 1);

use Psr\Log\LogLevel;
use Ramsey\Uuid\Doctrine\UuidType;

return static function (string $appEnv) {
    $settings = [
        'env' => $appEnv,

        'di_compilation_path' => __DIR__ . '/../var/cache/di_compilation',
        'encryption_key' => $_ENV['ENCRYPTION_KEY'],
        # "ISO 8601" TTL intervals: https://en.wikipedia.org/wiki/ISO_8601#Durations
        'access_token_ttl' => $_ENV['ACCESS_TOKEN_TTL'] ?? 'PT5M',
        'refresh_token_ttl' => $_ENV['REFRESH_TOKEN_TTL'] ?? 'P1M',

        // Logger
        'logger' => [
            'name' => 'general',
            'path' => $_ENV['LOGGER_PATH'] ?? 'php://stdout',
            'level' => $_ENV['LOGGER_LEVEL'] ?? LogLevel::DEBUG,
        ],

        // Database (doctrine)
        'doctrine' => [
            'dev_mode' => $appEnv === 'dev',
            'cache_dir' => __DIR__ . '/../var/doctrine/cache',
            'proxy_dir' => __DIR__ . '/../var/doctrine/proxy',
            'metadata_dirs' => [__DIR__ . '/../src/Entity'],
            'types' => [
                UuidType::NAME => UuidType::class,
            ],
            'connection' => [
                'dbname' => $_ENV['DATABASE_DBNAME'] ?? null,
                'user' => $_ENV['DATABASE_USER'] ?? null,
                'password' => $_ENV['DATABASE_PASSWORD'] ?? null,
                'host' => $_ENV['DATABASE_HOST'] ?? null,
                'port' => $_ENV['DATABASE_PORT'] ?? null,
                'driver' => $_ENV['DATABASE_DRIVER'] ?? null,
                'charset' => 'utf-8',
            ],
        ],
    ];

    if ($appEnv === 'dev') {
        // Overrides for development mode
        $settings['di_compilation_path'] = null;
    }

    return $settings;
};
