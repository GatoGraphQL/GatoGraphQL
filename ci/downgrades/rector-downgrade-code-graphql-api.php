<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

require_once __DIR__ . '/rector-downgrade-code-shared.php';

return static function (ContainerConfigurator $containerConfigurator): void {
    // Shared configuration
    doCommonContainerConfiguration($containerConfigurator);

    $monorepoDir = dirname(__DIR__, 2);
    $pluginDir = $monorepoDir . '/layers/GraphQLAPIForWP/plugins/graphql-api-for-wp';

    // get parameters
    $parameters = $containerConfigurator->parameters();

    // files to skip downgrading
    $parameters->set(Option::SKIP, [
        // Skip tests
        '*/tests/*',
        '*/test/*',
        '*/Test/*',

        // The GraphQL API plugin does not require the REST package
        // So ignore all code depending on it, or it throws error:
        //   "Could not process
        //   "vendor/pop-schema/pages/src/ConditionalOnComponent/RESTAPI/RouteModuleProcessors/EntryRouteModuleProcessor.php" file, due to:
        //   "Analyze error: "Class PoP\RESTAPI\RouteModuleProcessors\AbstractRESTEntryRouteModuleProcessor not found."
        '*/ConditionalOnComponent/RESTAPI/*',

        // Even when downgrading all packages, skip Symfony's polyfills
        $pluginDir . '/vendor/symfony/polyfill-*',

        // Skip since they are not needed and they fail
        $pluginDir . '/vendor/composer/*',
        $pluginDir . '/vendor/lkwdwrd/wp-muplugin-loader/*',

        // These are skipped in the .sh since it's faster
        // // All the "migrate" folders
        // $pluginDir . '/vendor/getpop/migrate-*/*',
        // $pluginDir . '/vendor/pop-schema/migrate-*/*',
        // $pluginDir . '/vendor/graphql-by-pop/migrate-*/*',
        // // For local dependencies, skip the tests
        // $pluginDir . '/vendor/getpop/*/tests/*',
        // $pluginDir . '/vendor/pop-schema/*/tests/*',
        // $pluginDir . '/vendor/graphql-by-pop/*/tests/*',

        // Ignore errors from classes we don't have in our environment,
        // or that come from referencing a class present in DEV, not PROD
        $pluginDir . '/vendor/symfony/cache/Adapter/MemcachedAdapter.php',
        $pluginDir . '/vendor/symfony/cache/DoctrineProvider.php',
        $pluginDir . '/vendor/symfony/cache/Messenger/EarlyExpirationHandler.php',
        $pluginDir . '/vendor/symfony/string/Slugger/AsciiSlugger.php',
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
        define('ABSPATH', $pluginDir . '/vendor/wordpress/wordpress/');
    }
};
