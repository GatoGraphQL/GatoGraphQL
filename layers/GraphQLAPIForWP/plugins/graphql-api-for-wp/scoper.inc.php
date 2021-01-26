<?php

declare(strict_types=1);

use Isolated\Symfony\Component\Finder\Finder;

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
 * The only exception is getpop/routing-wp, which uses Brain\Cortex:
 *
 * in getpop/routing-wp/src/Component.php:
 *   use Brain\Cortex;
 *
 * in getpop/routing-wp/src/Hooks/SetupCortexHookSet.php:
 *   use Brain\Cortex\Route\RouteCollectionInterface;
 *   use Brain\Cortex\Route\RouteInterface;
 *   use Brain\Cortex\Route\QueryRoute;
 *
 * Then, manually add these 2 files to scope Brain\Cortex.
 * This works without side effects, because there are no WordPress stubs in them.
 */
return [
    'finders' => [
        // Scope packages under vendor/, excluding local WordPress packages
        Finder::create()
            ->files()
            ->ignoreVCS(true)
            // ->notName('/LICENSE|.*\\.md|.*\\.dist|composer\\.json|composer\\.lock/')
            ->exclude([
                'getpop/*-wp',
                'pop-schema/*-wp',
            ])
            ->in('vendor'),
        Finder::create()->append([
            'vendor/getpop/routing-wp/src/Component.php',
            'vendor/getpop/routing-wp/src/Hooks/SetupCortexHookSet.php',
        ])
    ],
    'files-whitelist' => [
        // do not prefix "trigger_deprecation" from symfony - https://github.com/symfony/symfony/commit/0032b2a2893d3be592d4312b7b098fb9d71aca03
        // these paths are relative to this file location, so it should be in the root directory
        'vendor/symfony/deprecation-contracts/function.php',
        // avoid pre-slashing everything
        'composer.json',
    ],
    'whitelist' => [
        // own namespaces
        'PoP\*',
        'PoPSchema\*',
        'GraphQLByPoP\*',
        'GraphQLAPI\*',
        // for config.php  Symfony PHP Configs
        // 'Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator',
        // 'Composer\*',
    ],
];
