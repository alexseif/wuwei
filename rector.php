<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Symfony\Set\SymfonySetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    // Register sources
    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    // Define PHP version
    $rectorConfig->phpVersion(PhpVersion::PHP_82);

    // Symfony specific rules
    $rectorConfig->sets([
        SymfonySetList::SYMFONY_62,
        SymfonySetList::SYMFONY_CODE_QUALITY,
        SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
    ]);

    // PHP 8.2 features
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_82
    ]);

    // Skip certain files/directories if needed
    $rectorConfig->skip([
        __DIR__ . '/src/Kernel.php',
        __DIR__ . '/var',
    ]);
}; 