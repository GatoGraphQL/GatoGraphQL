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
 * Notice this must be executed everywhere (unlike the "external" scoping),
 * including src/ and "-wp" packages, and also "vendor/" (so that 2 different
 * standalone plugins don't have conflicting dependencies)
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
            ->path([
                // Include own source
                '#^src/#',
                // Include own libraries
                '#^vendor/[getpop|gatographql|graphql\-by\-pop|pop\-api|pop\-backbone|pop\-cms\-schema|pop\-schema|pop\-wp\-schema]/#',
            ])
            ->in(convertRelativeToFullPath()),
    ],
    'exclude-namespaces' => [
        // Own namespaces
        // Watch out! Do NOT alter the order of PoPSchema, PoPWPSchema and PoP!
        // If PoP comes first, then PoPSchema is still scoped!
        '/^(?!.*(PoPAPI|PoPBackbone|PoPCMSSchema|PoPSchema|PoPWPSchema|PoP|GraphQLByPoP|GatoGraphQL))/',
    ],
];
