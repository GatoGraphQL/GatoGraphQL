<?php

declare(strict_types=1);

use Isolated\Symfony\Component\Finder\Finder;
use PoP\Root\Helpers\ScopingHelpers;

require_once __DIR__ . '/scoper-shared.inc.php';

// Load code from the plugin to make the logic DRY
require_once dirname(__DIR__, 4) . '/layers/Engine/packages/root/src/Constants/Scoping.php';
require_once dirname(__DIR__, 4) . '/layers/Engine/packages/root/src/Helpers/ScopingHelpers.php';

/**
 * Must only scope the packages in vendor/, because:
 *
 * - config/ references only local configuration
 * - src/ references only local classes (with one exception), which need not be scoped
 * - vendor/ references external classes (excluding local -wp packages), which need be scoped
 *
 * In addition, we must exclude all the local WordPress packages,
 * because scoping WordPress functions does NOT work.
 * @see https://github.com/humbug/php-scoper/issues/303
 *
 * Excluding the WordPress packages is feasible, because they do
 * not reference any external library.
 *
 * The only exceptions are:
 * 
 * 1. getpop/root-wp, which uses Brain\Cortex:
 *
 * in getpop/root-wp/src/Module.php:
 *   use Brain\Cortex;
 *
 * in getpop/root-wp/src/Hooks/SetupCortexRoutingHookSet.php:
 *   use Brain\Cortex\Route\RouteCollectionInterface;
 *   use Brain\Cortex\Route\RouteInterface;
 *   use Brain\Cortex\Route\QueryRoute;
 *
 * Then, manually add these 2 files to scope Brain\Cortex.
 * This works without side effects, because there are no WordPress stubs in them.
 *
 * @return array<string,mixed>
 */
$pluginName = 'Gato GraphQL';
return [
    'prefix' => ScopingHelpers::getPluginExternalScopingTopLevelNamespace($pluginName),
    'finders' => [
        // Scope packages under vendor/, excluding local WordPress packages
        Finder::create()
            ->files()
            ->ignoreVCS(true)
            ->notName('/.*\\.md|.*\\.dist|composer\\.lock/')
            ->exclude([
                'tests',
            ])
            ->notPath([
                // Exclude all libraries for WordPress
                // 1. Exclude libraries ending in "-wp" from general packages
                '#[getpop|graphql\-by\-pop|pop\-api|pop\-cms\-schema]/[a-zA-Z0-9_-]*-wp/#',
                // 2. Exclude all libraries from WPSchema
                '#pop-wp-schema/#',
                // Exclude all composer.json from own libraries (they get broken!)
                '#[getpop|gatographql|graphql\-by\-pop|pop\-api|pop\-backbone|pop\-cms\-schema|pop\-schema|pop\-wp\-schema]/*/composer.json#',
                // Exclude libraries
                '#symfony/deprecation-contracts/#',
                // Exclude tests from libraries
                /**
                 * Gato GraphQL plugin: This code is commented out as it is
                 * not needed anymore, because package `brain/cortex`
                 * is not loaded
                 *
                 * @see layers/Engine/packages/root-wp/src/Module.php
                 */
                // '#nikic/fast-route/test/#',
                '#psr/log/Psr/Log/Test/#',
                '#symfony/dom-crawler/Test/#',
                '#symfony/http-foundation/Test/#',
                '#symfony/service-contracts/Test/#',
                '#michelf/php-markdown/test/#',
            ])
            ->in(convertRelativeToFullPath('vendor')),
        /**
         * Gato GraphQL plugin: This code is commented out as it is
         * not needed anymore, because package `brain/cortex`
         * is not loaded
         *
         * @see layers/Engine/packages/root-wp/src/Module.php
         */
        // Finder::create()->append([
        //     convertRelativeToFullPath('vendor/getpop/root-wp/src/Module.php'),
        //     convertRelativeToFullPath('vendor/getpop/root-wp/src/Hooks/SetupCortexRoutingHookSet.php'),
        // ])
    ],
    'exclude-namespaces' => [
        // Own namespaces
        // Watch out! Do NOT alter the order of PoPSchema, PoPWPSchema and PoP!
        // If PoP comes first, then PoPSchema is still scoped!
        'PoPAPI',
        'PoPBackbone',
        'PoPCMSSchema',
        'PoPIncludes',
        'PoPSchema',
        'PoPWPSchema',
        'PoP',
        'GraphQLByPoP',
        'GatoGraphQL',
        
        // No need to exclude the container, as its code is generated on runtime
        // /**
        //  * Own container cache namespace
        //  * 
        //  * Watch out! This value is being hardcoded!
        //  * 
        //  * In the application, it can be overridden via code:
        //  * 
        //  *   - ContainerBuilderFactory::getContainerNamespace()
        //  *   - SystemContainerBuilderFactory::getContainerNamespace()
        //  * 
        //  * But can't reference these classes here, since they are not found
        //  * (unless adding the files to the autoload path)
        //  */
        // 'PoPContainer',
    ],
    'patchers' => [
        gatoGraphQLScopingConfigurationPatcher(...),
    ],
];