<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

require_once __DIR__ . '/rector-downgrade-code-shared.php';

return static function (ContainerConfigurator $containerConfigurator): void {
    // Shared configuration
    doCommonContainerConfiguration($containerConfigurator);

    // get parameters
    $parameters = $containerConfigurator->parameters();

    // Rector relies on autoload setup of your project; Composer autoload is included by default; to add more:
    $parameters->set(Option::BOOTSTRAP_FILES, [
        // full directory
        __DIR__ . '/../../vendor/php-stubs/wordpress-stubs/wordpress-stubs.php',
        // Avoid error: "Class EM_Event not found"
        __DIR__ . '/../../stubs/wpackagist-plugin/events-manager/em-stubs.php',
    ]);

    // files to skip downgrading
    $parameters->set(Option::SKIP, [
        // Ignore downgrading the monorepo source
        __DIR__ . '/../../src/*',

        // Even when downgrading all packages, skip Symfony's polyfills
        __DIR__ . '/../../vendor/symfony/polyfill-*',

        // Skip since they are not needed and they fail
        __DIR__ . '/../../vendor/composer/*',
        __DIR__ . '/../../vendor/lkwdwrd/wp-muplugin-loader/*',

        // Ignore errors from classes we don't have in our environment,
        // or that come from referencing a class present in DEV, not PROD
        __DIR__ . '/../../vendor/symfony/cache/Adapter/MemcachedAdapter.php',
        __DIR__ . '/../../vendor/symfony/cache/DoctrineProvider.php',
        __DIR__ . '/../../vendor/symfony/cache/Messenger/EarlyExpirationHandler.php',
        __DIR__ . '/../../vendor/symfony/string/Slugger/AsciiSlugger.php',
    ]);
};
