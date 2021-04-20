<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

require_once __DIR__ . '/rector-downgrade-code-shared.php';

return static function (ContainerConfigurator $containerConfigurator): void {
    // Shared configuration
    doCommonContainerConfiguration($containerConfigurator);

    $monorepoDir = dirname(__DIR__, 2);

    // get parameters
    $parameters = $containerConfigurator->parameters();

    // Rector relies on autoload setup of your project; Composer autoload is included by default; to add more:
    $parameters->set(Option::BOOTSTRAP_FILES, [
        // full directory
        $monorepoDir . '/vendor/php-stubs/wordpress-stubs/wordpress-stubs.php',
        // Avoid error: "Class EM_Event not found"
        $monorepoDir . '/stubs/wpackagist-plugin/events-manager/em-stubs.php',
    ]);

    // files to skip downgrading
    $parameters->set(Option::SKIP, [
        // Ignore downgrading the monorepo source
        $monorepoDir . '/src/*',

        // Even when downgrading all packages, skip Symfony's polyfills
        $monorepoDir . '/vendor/symfony/polyfill-*',

        // Skip since they are not needed and they fail
        $monorepoDir . '/vendor/composer/*',
        $monorepoDir . '/vendor/lkwdwrd/wp-muplugin-loader/*',

        // Ignore errors from classes we don't have in our environment,
        // or that come from referencing a class present in DEV, not PROD
        $monorepoDir . '/vendor/symfony/cache/Adapter/MemcachedAdapter.php',
        $monorepoDir . '/vendor/symfony/cache/DoctrineProvider.php',
        $monorepoDir . '/vendor/symfony/cache/Messenger/EarlyExpirationHandler.php',
        $monorepoDir . '/vendor/symfony/string/Slugger/AsciiSlugger.php',
    ]);
};
