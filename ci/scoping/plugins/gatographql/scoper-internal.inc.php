<?php

declare(strict_types=1);

use Isolated\Symfony\Component\Finder\Finder;

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
function convertRelativeToFullPath(?string $relativePath = null): string
{
    $monorepoDir = dirname(__DIR__, 4);
    $pluginDir = $monorepoDir . '/layers/GatoGraphQLForWP/plugins/gatographql';
    if ($relativePath === null) {
        return $pluginDir;
    }
    return $pluginDir . '/' . $relativePath;
}
return [
    'prefix' => 'PrefixedByGatoStandalone',
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
    'exclude-namespaces' => [
        // Commented out as this code doesn't work, and then ALL code
        // is being prefixed, including 3rd-party dependencies again
        // // Own namespaces
        // // Watch out! Do NOT alter the order of PoPSchema, PoPWPSchema and PoP!
        // // If PoP comes first, then PoPSchema is still scoped!
        // '/^(?!.*(PoPAPI|PoPBackbone|PoPCMSSchema|PoPSchema|PoPWPSchema|PoP|GraphQLByPoP|GatoGraphQL))/',
    ],
    'patchers' => [
        function (string $filePath, string $prefix, string $content): string {
            // $fileFolder = dirname($filePath);
            // /**
            //  * Symfony Polyfill packages.
            //  * The bootstrap*.php files must register functions under the global namespace,
            //  * and the other ones must register constants on the global namespace,
            //  * so remove the namespaced after it's added.
            //  * These files can't be whitelisted, because they may reference a prefixed class
            //  */
            // // Pattern to identify Symfony Polyfill bootstrap files
            // // - vendor/symfony/polyfill-mbstring/bootstrap80.php
            // // - etc
            // $pattern = '#' . convertRelativeToFullPath('vendor/symfony/polyfill-[a-zA-Z0-9_-]*/bootstrap.*\.php') . '#';
            // $symfonyPolyfillFilesWithGlobalClass = array_map(
            //     convertRelativeToFullPath(...),
            //     [
            //         'vendor/symfony/polyfill-intl-normalizer/Resources/stubs/Normalizer.php',
            //         'vendor/symfony/polyfill-php80/Resources/stubs/Attribute.php',
            //         'vendor/symfony/polyfill-php80/Resources/stubs/Stringable.php',
            //         'vendor/symfony/polyfill-php80/Resources/stubs/UnhandledMatchError.php',
            //         'vendor/symfony/polyfill-php80/Resources/stubs/ValueError.php',
            //         'vendor/symfony/polyfill-php83/Resources/stubs/DateError.php',
            //     ]
            // );
            // $symfonyPolyfillFoldersWithGlobalClass = array_map(
            //     convertRelativeToFullPath(...),
            //     [
            //         'vendor/symfony/polyfill-php83/Resources/stubs',
            //     ]
            // );
            // $isSymfonyPolyfillFileWithGlobalClass = in_array($filePath, $symfonyPolyfillFilesWithGlobalClass)
            //     || in_array($fileFolder, $symfonyPolyfillFoldersWithGlobalClass);
            // if (
            //     $isSymfonyPolyfillFileWithGlobalClass
            //     || preg_match($pattern, $filePath)
            // ) {
            //     // Remove the namespace
            //     $content = str_replace(
            //         sprintf("namespace %s;", $prefix),
            //         '',
            //         $content
            //     );

            //     // Remove the namespace from all the `function_exists` in bootstrap.php
            //     $content = str_replace(
            //         sprintf("function_exists('%s\\\\", $prefix),
            //         "function_exists('",
            //         $content
            //     );

            //     // Comment out the class_alias too
            //     if ($isSymfonyPolyfillFileWithGlobalClass) {
            //         $content = str_replace(
            //             sprintf("\class_alias('%s\\\\", $prefix),
            //             sprintf("//\class_alias('%s\\\\", $prefix),
            //             $content
            //         );
            //     }

            //     return $content;
            // }

            return $content;
        },
    ],
];
