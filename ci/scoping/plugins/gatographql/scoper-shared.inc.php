<?php

declare(strict_types=1);

function convertRelativeToFullPath(?string $relativePath = null): string
{
    $monorepoDir = dirname(__DIR__, 4);
    $pluginDir = $monorepoDir . '/layers/GatoGraphQLForWP/plugins/gatographql';
    if ($relativePath === null) {
        return $pluginDir;
    }
    return $pluginDir . '/' . $relativePath;
}

function gatoGraphQLScopingConfigurationPatcher(string $filePath, string $prefix, string $content): string {
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
}