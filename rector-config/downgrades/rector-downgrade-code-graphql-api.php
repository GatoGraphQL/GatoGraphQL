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
        __DIR__ . '/../../layers/GraphQLAPIForWP/plugins/graphql-api-for-wp/vendor/php-stubs/wordpress-stubs/wordpress-stubs.php',
    ]);

    // files to skip downgrading
    $parameters->set(Option::SKIP, [
        // The GraphQL API plugin does not require the REST package
        // So ignore all code depending on it, or it throws error:
        //   "Could not process
        //   "vendor/pop-schema/pages/src/Conditional/RESTAPI/RouteModuleProcessors/EntryRouteModuleProcessor.php" file, due to:
        //   "Analyze error: "Class PoP\RESTAPI\RouteModuleProcessors\AbstractRESTEntryRouteModuleProcessor not found."
        '*/Conditional/RESTAPI/*',

        // Even when downgrading all packages, skip Symfony's polyfills
        __DIR__ . '/../../layers/GraphQLAPIForWP/plugins/graphql-api-for-wp/vendor/symfony/polyfill-*',

        // Skip since they are not needed and they fail
        __DIR__ . '/../../layers/GraphQLAPIForWP/plugins/graphql-api-for-wp/vendor/composer/*',
        __DIR__ . '/../../layers/GraphQLAPIForWP/plugins/graphql-api-for-wp/vendor/lkwdwrd/wp-muplugin-loader/*',

        // These are skipped in the .sh since it's faster
        // // All the "migrate" folders
        // __DIR__ . '/../../layers/GraphQLAPIForWP/plugins/graphql-api-for-wp/vendor/getpop/migrate-*/*',
        // __DIR__ . '/../../layers/GraphQLAPIForWP/plugins/graphql-api-for-wp/vendor/pop-schema/migrate-*/*',
        // __DIR__ . '/../../layers/GraphQLAPIForWP/plugins/graphql-api-for-wp/vendor/graphql-by-pop/migrate-*/*',
        // // For local dependencies, skip the tests
        // __DIR__ . '/../../layers/GraphQLAPIForWP/plugins/graphql-api-for-wp/vendor/getpop/*/tests/*',
        // __DIR__ . '/../../layers/GraphQLAPIForWP/plugins/graphql-api-for-wp/vendor/pop-schema/*/tests/*',
        // __DIR__ . '/../../layers/GraphQLAPIForWP/plugins/graphql-api-for-wp/vendor/graphql-by-pop/*/tests/*',

        // Ignore errors from classes we don't have in our environment,
        // or that come from referencing a class present in DEV, not PROD
        __DIR__ . '/../../layers/GraphQLAPIForWP/plugins/graphql-api-for-wp/vendor/symfony/cache/Adapter/MemcachedAdapter.php',
        __DIR__ . '/../../layers/GraphQLAPIForWP/plugins/graphql-api-for-wp/vendor/symfony/cache/DoctrineProvider.php',
        __DIR__ . '/../../layers/GraphQLAPIForWP/plugins/graphql-api-for-wp/vendor/symfony/cache/Messenger/EarlyExpirationHandler.php',
        __DIR__ . '/../../layers/GraphQLAPIForWP/plugins/graphql-api-for-wp/vendor/symfony/string/Slugger/AsciiSlugger.php',
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
        define('ABSPATH', __DIR__ . '/../../layers/GraphQLAPIForWP/plugins/graphql-api-for-wp/vendor/wordpress/wordpress/');
    }
};
