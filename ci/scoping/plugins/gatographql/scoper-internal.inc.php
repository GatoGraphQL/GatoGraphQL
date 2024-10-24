<?php

declare(strict_types=1);

use Isolated\Symfony\Component\Finder\Finder;

require_once __DIR__ . '/scoper-shared.inc.php';

/**
 * Scope own classes for creating a standalone plugin.
 *
 * Whitelisting classes to scope is not supported by PHP-Scoper:
 *
 * @see https://github.com/humbug/php-scoper/issues/378#issuecomment-687601833
 *
 * Then, instead, create a regex that excludes all classes except
 * the ones we're looking for.
 *
 * Notice this must be executed in all the local source code
 * and local packages.
 */
return [
    /**
     * Watch out! This name is hardcoded, but it must be EXACTLY the same
     * as the calculated name for each plugin.
     * 
     * For instance, plugin "Gato GraphQL" will have the top-level
     * namespace "InternallyPrefixedByGatoGraphQL".
     *
     * @see layers/GatoGraphQLForWP/plugins/gatographql/src/PluginSkeleton/AbstractPlugin.php `__construct`
     */
    'prefix' => 'InternallyPrefixedByGatoGraphQL',
    'finders' => [
        Finder::create()
            ->files()
            ->ignoreVCS(true)
            ->notName('/.*\\.md|.*\\.dist|composer\\.lock/')
            ->exclude([
                'tests',
            ])
            ->notPath([
                /**
                 * For some reason it also prefixes content in file:
                 *   vendor/symfony/dependency-injection/Dumper/PhpDumper.php
                 * and a few more, so directly exclude all other libraries.
                 */
                '#vendor/guzzlehttp/#',
                '#vendor/jrfnl/#',
                '#vendor/league/#',
                '#vendor/masterminds/#',
                '#vendor/michelf/#',
                '#vendor/psr/#',
                '#vendor/ralouphie/#',
                '#vendor/symfony/#',
            ])
            // ->path(
            //     // Include own source and own libraries only
            //     '#^src/|^vendor/[getpop|gatographql|graphql\-by\-pop|pop\-api|pop\-backbone|pop\-cms\-schema|pop\-schema|pop\-wp\-schema]/#',
            // )
            ->in(convertRelativeToFullPath()),
    ],
    'exclude-namespaces' => [
        // Own namespaces
        // Watch out! Do NOT alter the order of PoPSchema, PoPWPSchema and PoP!
        // If PoP comes first, then PoPSchema is still scoped!
        '/^(?!.*(PoPAPI|PoPBackbone|PoPCMSSchema|PoPIncludes|PoPSchema|PoPWPSchema|PoP|GraphQLByPoP|GatoGraphQL))/',
    ],
    'exclude-functions' => [
        // Functions referenced in files:
        // layers/GatoGraphQLForWP/plugins/gatographql/includes/startup.php
        'wp_convert_hr_to_bytes',
        'add_action',
        // // vendor/gatographql/gatographql/includes/schema-editing-access-capabilities.php
        // 'register_activation_hook',
        // 'register_deactivation_hook',
    ],
];
