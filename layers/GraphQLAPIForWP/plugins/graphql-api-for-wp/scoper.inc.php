<?php

declare(strict_types=1);

use Isolated\Symfony\Component\Finder\Finder;

/**
 * Must only scope the packages in _vendor/, because:
 *
 * - config/ references only local configuration
 * - src/ references only local classes (with one exception), which need not be scoped
 * - _vendor/ references external classes (excluding local -wp packages), which need be scoped
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
    'prefix' => 'PrefixedByPoP',
    'finders' => [
        // Scope packages under _vendor/, excluding local WordPress packages
        Finder::create()
            ->files()
            ->ignoreVCS(true)
            ->notName('/LICENSE|.*\\.md|.*\\.dist|composer\\.json|composer\\.lock/')
            ->notPath([
                // Exclude libraries ending in "-wp"
                '#getpop/[a-zA-Z0-9_-]*-wp/#',
                '#pop-schema/[a-zA-Z0-9_-]*-wp/#',
                '#graphql-by-pop/[a-zA-Z0-9_-]*-wp/#',
                // Exclude libraries
                '#symfony/deprecation-contracts/#',
                '#ralouphie/getallheaders/#',
            ])
            ->in('_vendor'),
        Finder::create()->append([
            '_vendor/getpop/routing-wp/src/Component.php',
            '_vendor/getpop/routing-wp/src/Hooks/SetupCortexHookSet.php',
        ])
    ],
    'whitelist' => [
        // Own namespaces
        // Watch out! Do NOT alter the order of PoPSchema and PoP!
        // If PoP comes first, then PoPSchema is still scoped!
        'PoPSchema\*',
        'PoP\*',
        'GraphQLByPoP\*',
        'GraphQLAPI\*',
        // Own container cache
        'PoPContainer\*',
    ],
    'patchers' => [
        function (string $filePath, string $prefix, string $content): string {
            /**
             * File _vendor/nikic/fast-route/src/bootstrap.php has this code:
             *
             * if (\strpos($class, 'FastRoute\\') === 0) {
             *   $name = \substr($class, \strlen('FastRoute'));
             *
             * Fix it
             */
            if ($filePath === __DIR__ . DIRECTORY_SEPARATOR . '_vendor/nikic/fast-route/src/bootstrap.php') {
                return str_replace(
                    ["'FastRoute\\\\'", "'FastRoute'"],
                    ["'${prefix}\\\\FastRoute\\\\'", "'${prefix}\\\\FastRoute'"],
                    $content
                );
            }
            /**
             * Brain/Cortex is prefixing classes \WP and \WP_Rewrite
             * Avoid it!
             */
            if (str_starts_with($filePath, __DIR__ . DIRECTORY_SEPARATOR . '_vendor/brain/cortex/')) {
                return str_replace(
                    "\\${prefix}\\WP",
                    "\\WP",
                    $content
                );
            }
            /**
             * Symfony Polyfill packages.
             * The bootstrap*.php files must register functions under the global namespace,
             * and the other ones must register constants on the global namespace,
             * so remove the namespaced after it's added.
             * These files can't be whitelisted, because they may reference a prefixed class
             */
            // Pattern to identify Symfony Polyfill bootstrap files
            // - _vendor/symfony/polyfill-mbstring/bootstrap80.php
            // - _vendor/symfony/polyfill-php72/bootstrap.php
            // - etc
            $pattern = '#' . __DIR__ . '/_vendor/symfony/polyfill-[a-zA-Z0-9_-]*/bootstrap.*\.php#';
            $symfonyPolyfillFilesWithGlobalClass = array_map(
                function (string $relativePath): string {
                    return __DIR__ . '/_vendor/' . $relativePath;
                },
                [
                    'symfony/polyfill-intl-normalizer/Resources/stubs/Normalizer.php',
                    'symfony/polyfill-php73/Resources/stubs/JsonException.php',
                    'symfony/polyfill-php80/Resources/stubs/Attribute.php',
                    'symfony/polyfill-php80/Resources/stubs/Stringable.php',
                    'symfony/polyfill-php80/Resources/stubs/UnhandledMatchError.php',
                    'symfony/polyfill-php80/Resources/stubs/ValueError.php',
                ]
            );
            $isSymfonyPolyfillFileWithGlobalClass = in_array($filePath, $symfonyPolyfillFilesWithGlobalClass);
            if (
                $isSymfonyPolyfillFileWithGlobalClass
                || preg_match($pattern, $filePath)
            ) {
                // Remove the namespace
                $content = str_replace(
                    "namespace ${prefix};",
                    '',
                    $content
                );

                // Comment out the class_alias too
                if ($isSymfonyPolyfillFileWithGlobalClass) {
                    $content = str_replace(
                        "\class_alias('${prefix}\\\\",
                        "//\class_alias('${prefix}\\\\",
                        $content
                    );
                }

                return $content;
            }
            return $content;
        },
    ],
];
