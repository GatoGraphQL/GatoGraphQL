<?php

declare(strict_types=1);

use Isolated\Symfony\Component\Finder\Finder;

require_once __DIR__ . '/scoper-shared.inc.php';

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
 */
$pluginConciseNamespace = 'GatoGQL';
return [
    'prefix' => 'PrefixedBy' . $pluginConciseNamespace,
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
        function (string $filePath, string $prefix, string $content): string {
            /**
             * Gato GraphQL plugin: This code is commented out as it is
             * not needed anymore, because package `brain/cortex`
             * is not loaded
             *
             * @see layers/Engine/packages/root-wp/src/Module.php
             */            
            // /**
            //  * File vendor/nikic/fast-route/src/bootstrap.php has this code:
            //  *
            //  * if (\strpos($class, 'FastRoute\\') === 0) {
            //  *   $name = \substr($class, \strlen('FastRoute'));
            //  *
            //  * Fix it
            //  */
            // if ($filePath === convertRelativeToFullPath('vendor/nikic/fast-route/src/bootstrap.php')) {
            //     return str_replace(
            //         ["'FastRoute\\\\'", "'FastRoute'"],
            //         [sprintf("'%s\\\\FastRoute\\\\'", $prefix), sprintf("'%s\\\\FastRoute'", $prefix)],
            //         $content
            //     );
            // }
            // /**
            //  * Brain/Cortex is prefixing classes \WP and \WP_Rewrite
            //  * Avoid it!
            //  */
            // if (str_starts_with($filePath, convertRelativeToFullPath('vendor/brain/cortex/'))) {
            //     return str_replace(
            //         sprintf("\\%s\\WP", $prefix),
            //         "\\WP",
            //         $content
            //     );
            // }

            $fileFolder = dirname($filePath);
            /**
             * Symfony Polyfill packages.
             * The bootstrap*.php files must register functions under the global namespace,
             * and the other ones must register constants on the global namespace,
             * so remove the namespaced after it's added.
             * These files can't be whitelisted, because they may reference a prefixed class
             */
            // Pattern to identify Symfony Polyfill bootstrap files
            // - vendor/symfony/polyfill-mbstring/bootstrap80.php
            // - etc
            $pattern = '#' . convertRelativeToFullPath('vendor/symfony/polyfill-[a-zA-Z0-9_-]*/bootstrap.*\.php') . '#';
            $symfonyPolyfillFilesWithGlobalClass = array_map(
                convertRelativeToFullPath(...),
                [
                    'vendor/symfony/polyfill-intl-normalizer/Resources/stubs/Normalizer.php',
                    'vendor/symfony/polyfill-php80/Resources/stubs/Attribute.php',
                    'vendor/symfony/polyfill-php80/Resources/stubs/Stringable.php',
                    'vendor/symfony/polyfill-php80/Resources/stubs/UnhandledMatchError.php',
                    'vendor/symfony/polyfill-php80/Resources/stubs/ValueError.php',
                    'vendor/symfony/polyfill-php83/Resources/stubs/DateError.php',
                ]
            );
            $symfonyPolyfillFoldersWithGlobalClass = array_map(
                convertRelativeToFullPath(...),
                [
                    'vendor/symfony/polyfill-php83/Resources/stubs',
                ]
            );
            $isSymfonyPolyfillFileWithGlobalClass = in_array($filePath, $symfonyPolyfillFilesWithGlobalClass)
                || in_array($fileFolder, $symfonyPolyfillFoldersWithGlobalClass);
            if (
                $isSymfonyPolyfillFileWithGlobalClass
                || preg_match($pattern, $filePath)
            ) {
                // Remove the namespace
                $content = str_replace(
                    sprintf("namespace %s;", $prefix),
                    '',
                    $content
                );

                // Remove the namespace from all the `function_exists` in bootstrap.php
                $content = str_replace(
                    sprintf("function_exists('%s\\\\", $prefix),
                    "function_exists('",
                    $content
                );

                // Comment out the class_alias too
                if ($isSymfonyPolyfillFileWithGlobalClass) {
                    $content = str_replace(
                        sprintf("\class_alias('%s\\\\", $prefix),
                        sprintf("//\class_alias('%s\\\\", $prefix),
                        $content
                    );
                }

                return $content;
            }

            return $content;
        },
    ],
];
