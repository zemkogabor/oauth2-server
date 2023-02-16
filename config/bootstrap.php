<?php

declare(strict_types = 1);

use DI\ContainerBuilder;

require __DIR__ . '/../vendor/autoload.php';

$settings = (require __DIR__ . '/settings.php')(
    $_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? 'dev'
);

$containerBuilder = new ContainerBuilder();
if ($settings['di_compilation_path'] !== null) {
    $containerBuilder->enableCompilation($settings['di_compilation_path']);
}

(require __DIR__ . '/dependencies.php')($containerBuilder, $settings);

return $containerBuilder->build();
