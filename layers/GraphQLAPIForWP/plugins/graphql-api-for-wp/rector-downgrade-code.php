<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Rector\Set\ValueObject\DowngradeSetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    // get parameters
    $parameters = $containerConfigurator->parameters();

    // here we can define, what sets of rules will be applied
    $parameters->set(Option::SETS, [
        // @todo Uncomment when PHP 8.0 released
        // DowngradeSetList::PHP_80,
        DowngradeSetList::PHP_74,
        DowngradeSetList::PHP_73,
        DowngradeSetList::PHP_72,
    ]);

    // is your PHP version different from the one your refactor to? [default: your PHP version]
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_71);

    // Rector relies on autoload setup of your project; Composer autoload is included by default; to add more:
    $parameters->set(Option::AUTOLOAD_PATHS, [
        // full directory
        __DIR__ . '/vendor/wordpress/wordpress',
    ]);

    // files to skip downgrading
    $parameters->set(Option::SKIP, [
        // These are skipped in the .sh since it's faster
        // // All the "migrate" folders
        // __DIR__ . '/vendor/getpop/migrate-*/*',
        // __DIR__ . '/vendor/pop-schema/migrate-*/*',
        // __DIR__ . '/vendor/graphql-by-pop/migrate-*/*',
        // // For local dependencies, skip the tests
        // __DIR__ . '/vendor/getpop/*/tests/*',
        // __DIR__ . '/vendor/pop-schema/*/tests/*',
        // __DIR__ . '/vendor/graphql-by-pop/*/tests/*',

        // Individual classes that can be excluded because
        // they are not used by us, and they use classes
        // loaded with "require-dev" so it'd throw an error
        __DIR__ . '/vendor/symfony/cache/DoctrineProvider.php',
        __DIR__ . '/vendor/symfony/cache/Messenger/EarlyExpirationHandler.php',
        __DIR__ . '/vendor/symfony/string/Slugger/AsciiSlugger.php',
    ]);

    /**
     * This constant is defined in wp-load.php, but never loaded.
     * It is read when resolving class WP_Upgrader in Plugin.php.
     * Define it here again, or otherwise Rector fails with message:
     *
     * "PHP Warning:  Use of undefined constant ABSPATH -
     * assumed 'ABSPATH' (this will throw an Error in a future version of PHP)
     * in .../graphql-api-for-wp/vendor/wordpress/wordpress/wp-admin/includes/class-wp-upgrader.php
     * on line 13"
     */
    /** Define ABSPATH as this file's directory */
    if (!defined('ABSPATH')) {
        define('ABSPATH', __DIR__ . '/vendor/wordpress/wordpress/');
    }
};
