<?php

declare(strict_types = 1);

use Psr\Log\LogLevel;
use Ramsey\Uuid\Doctrine\UuidType;

return static function (string $appEnv) {
    $settings = [
        'env' => $appEnv,

        'di_compilation_path' => __DIR__ . '/../var/cache/di_compilation',
        'display_error_details' => false,
        'log_errors' => true,
        'encryption_key' => $_ENV['ENCRYPTION_KEY'],

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
        $settings['display_error_details'] = true;
    }

    return $settings;
};
