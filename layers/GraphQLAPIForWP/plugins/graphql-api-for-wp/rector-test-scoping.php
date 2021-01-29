<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\LogicalAnd\AndAssignsToSeparateLinesRector;
use Rector\Core\Configuration\Option;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

/**
 * This Rector configuration imports the fully qualified classnames
 * using `use`, and removing it from the body.
 * Rule `AndAssignsToSeparateLinesRector` is not needed, but we need
 * to run at least 1 rule.
 */
return static function (ContainerConfigurator $containerConfigurator): void {
    // get parameters
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::SETS, []);

    $services = $containerConfigurator->services();
    $services->set(AndAssignsToSeparateLinesRector::class);
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
    $parameters->set(Option::IMPORT_SHORT_CLASSES, false);

    // Rector relies on autoload setup of your project; Composer autoload is included by default; to add more:
    $parameters->set(Option::AUTOLOAD_PATHS, [
        __DIR__ . '/vendor/scoper-autoload.php',
        // // full directory
        // __DIR__ . '/vendor/php-stubs/wordpress-stubs/wordpress-stubs.php',
        // // Avoid error: "Class EM_Event not found"
        // __DIR__ . '/stubs/wpackagist-plugin/events-manager/em-stubs.php',
    ]);

    // files to rector
    $parameters->set(Option::PATHS, [
        __DIR__ . '/vendor',
    ]);

    // files to skip
    $parameters->set(Option::SKIP, [
        '*/tests/*',
        // Exclude migrate libraries
        __DIR__ . '/vendor/getpop/migrate-*',
        __DIR__ . '/vendor/pop-schema/migrate-*',
        // Exclude tests from libraries
        __DIR__ . '/vendor/nikic/fast-route/test/*',
        __DIR__ . '/vendor/psr/log/Psr/Log/Test/*',
        __DIR__ . '/vendor/symfony/service-contracts/Test/*',
        // Ignore errors from classes we don't have in our environment,
        // or that come from referencing a class present in DEV, not PROD
        __DIR__ . '/vendor/symfony/cache/Adapter/MemcachedAdapter.php',
        __DIR__ . '/vendor/symfony/cache/DataCollector/CacheDataCollector.php',
        __DIR__ . '/vendor/symfony/cache/DoctrineProvider.php',
        __DIR__ . '/vendor/symfony/cache/Messenger/EarlyExpirationHandler.php',
        __DIR__ . '/vendor/symfony/cache/Psr16Cache.php',
        __DIR__ . '/vendor/symfony/string/Slugger/AsciiSlugger.php',
        __DIR__ . '/vendor/symfony/yaml/Command/LintCommand.php',
    ]);

    // /**
    //  * This constant is defined in wp-load.php, but never loaded.
    //  * It is read when resolving class WP_Upgrader in Plugin.php.
    //  * Define it here again, or otherwise Rector fails with message:
    //  *
    //  * "PHP Warning:  Use of undefined constant ABSPATH -
    //  * assumed 'ABSPATH' (this will throw an Error in a future version of PHP)
    //  * in .../graphql-api-for-wp/vendor/wordpress/wordpress/wp-admin/includes/class-wp-upgrader.php
    //  * on line 13"
    //  */
    // /** Define ABSPATH as this file's directory */
    // if (!defined('ABSPATH')) {
    //     define('ABSPATH', __DIR__ . '/vendor/wordpress/wordpress/');
    // }
};
