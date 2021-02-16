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
function convertRelativeToFullPath(string $relativePath): string {
    return __DIR__ . '/vendor/' . $relativePath;
}
return [
    'prefix' => 'PrefixedByPoP',
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
                // Exclude libraries ending in "-wp"
                '#getpop/[a-zA-Z0-9_-]*-wp/#',
                '#pop-schema/[a-zA-Z0-9_-]*-wp/#',
                '#graphql-by-pop/[a-zA-Z0-9_-]*-wp/#',
                // Exclude all composer.json from own libraries (they get broken!)
                '#[getpop|pop\-schema|graphql\-by\-pop|graphql\-api]/*/composer.json#',
                // Exclude libraries
                '#symfony/deprecation-contracts/#',
                '#ralouphie/getallheaders/#',
                // Exclude tests from libraries
                '#nikic/fast-route/test/#',
                '#psr/log/Psr/Log/Test/#',
                '#symfony/service-contracts/Test/#',
            ])
            ->in('vendor'),
        Finder::create()->append([
            'vendor/getpop/routing-wp/src/Component.php',
            'vendor/getpop/routing-wp/src/Hooks/SetupCortexHookSet.php',
        ])
    ],
    'whitelist' => array_values(array_unique([
        // Own namespaces
        // Watch out! Do NOT alter the order of PoPSchema and PoP!
        // If PoP comes first, then PoPSchema is still scoped!
        'PoPSchema\*',
        'PoP\*',
        'GraphQLByPoP\*',
        'GraphQLAPI\*',
        // Own container cache namespace
        // Watch out! This value is being hardcoded!
        // In the application, it can be overriden via code:
        // - ContainerBuilderFactory::getContainerNamespace()
        // - SystemContainerBuilderFactory::getContainerNamespace()
        // But can't reference these classes here, since they are not found
        // (unless adding the files to the autoload path)
        'PoPContainer\*',
    ])),
    'files-whitelist' => [
        // Class Composer\InstalledVersions will be regenerated without scope when
        // doing `composer dumpautoload`, so skip it
        'vendor/composer/InstalledVersions.php',
    ],
    'patchers' => [
        function (string $filePath, string $prefix, string $content): string {
            /**
             * File vendor/nikic/fast-route/src/bootstrap.php has this code:
             *
             * if (\strpos($class, 'FastRoute\\') === 0) {
             *   $name = \substr($class, \strlen('FastRoute'));
             *
             * Fix it
             */
            if ($filePath === __DIR__ . DIRECTORY_SEPARATOR . 'vendor/nikic/fast-route/src/bootstrap.php') {
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
            if (str_starts_with($filePath, __DIR__ . DIRECTORY_SEPARATOR . 'vendor/brain/cortex/')) {
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
            // - vendor/symfony/polyfill-mbstring/bootstrap80.php
            // - vendor/symfony/polyfill-php72/bootstrap.php
            // - etc
            $pattern = '#' . __DIR__ . '/vendor/symfony/polyfill-[a-zA-Z0-9_-]*/bootstrap.*\.php#';
            $symfonyPolyfillFilesWithGlobalClass = array_map(
                'convertRelativeToFullPath',
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
            /**
             * In these files, it prefixes the return type `parent`.
             * Undo it!
             */
            $symfonyPolyfillFilesWithParentReturnType = array_map(
                'convertRelativeToFullPath',
                [
                    'symfony/string/AbstractUnicodeString.php',
                    'symfony/string/ByteString.php',
                    'symfony/string/UnicodeString.php',
                ]
            );
            if (in_array($filePath, $symfonyPolyfillFilesWithParentReturnType)) {
                return str_replace(
                    "\\${prefix}\\parent",
                    'parent',
                    $content
                );
            }

            /**
             * It changes the path to Parsedown source files in its composer.json
             * Undo it!
             */
            if ($filePath == convertRelativeToFullPath('erusev/parsedown/composer.json')) {
                return str_replace(
                    ['"\\/Parsedown\\/"', '"psr-4"'],
                    ['""', '"psr-0"'],
                    $content
                );
            }

            return $content;
        },
    ],
];
