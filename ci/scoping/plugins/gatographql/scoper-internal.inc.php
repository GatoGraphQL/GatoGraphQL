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
 * including src/ and "-wp" packages
 */
return [
    // Watch out! This name is hardcoded, but it must be EXACTLY the same
    // as the calculated name for each plugin
    // For instance, plugin "Gato GraphQL" will have the top-level namespace "GatoGraphQLScoped".
    // @see layers/GatoGraphQLForWP/plugins/gatographql/src/PluginSkeleton/AbstractPlugin.php `__construct`
    'prefix' => 'GatoGraphQLScoped',
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
                '#psr/log/Psr/Log/Test/#',
                '#symfony/dom-crawler/Test/#',
                '#symfony/http-foundation/Test/#',
                '#symfony/service-contracts/Test/#',
                '#michelf/php-markdown/test/#',
            ])
            ->in(convertRelativeToFullPath()),
    ],
];
