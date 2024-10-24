<?php

declare(strict_types=1);

namespace PoPIncludes\GatoGraphQL;

use GatoGraphQL\GatoGraphQL\PluginApp;
use PoP\Root\Environment as RootEnvironment;

class GatoGraphQL_Startup {
    /**
     * Validate that there is enough memory to run the plugin.
     *
     * > Note that to have no memory limit, set this directive to -1.
     *
     * @see https://www.php.net/manual/en/ini.core.php#ini.sect.resource-limits
     */
    public static function checkGatoGraphQLMemoryRequirements(string $pluginName): bool
    {
        $phpMemoryLimit = \ini_get('memory_limit');
        $phpMemoryLimitInBytes = \wp_convert_hr_to_bytes($phpMemoryLimit);
        if ($phpMemoryLimitInBytes !== -1) {
            // Minimum: 64MB
            $minRequiredPHPMemoryLimit = '64M';
            $minRequiredPHPMemoryLimitInBytes = \wp_convert_hr_to_bytes($minRequiredPHPMemoryLimit);
            if ($phpMemoryLimitInBytes < $minRequiredPHPMemoryLimitInBytes) {
                \add_action('admin_notices', function () use ($minRequiredPHPMemoryLimit, $phpMemoryLimit, $pluginName) {
                    printf(
                        '<div class="notice notice-error"><p>%s</p></div>',
                        sprintf(
                            __('Plugin <strong>%1$s</strong> requires at least <strong>%2$s</strong> of memory, however the server\'s PHP memory limit is set to <strong>%3$s</strong>. Please increase the memory limit to load %1$s.', 'gatographql'),
                            $pluginName,
                            $minRequiredPHPMemoryLimit,
                            $phpMemoryLimit
                        )
                    );
                });
                return false;
            }
        }
        return true;
    }

    /**
     * During development, due to symlinking in Lando, __FILE__ for bundled
     * extensions doesn't point to the expected location under "vendor",
     * but to the symlinked path.
     *
     * As a consequence, the PluginDir and PluginURL is not calculated
     * correctly, and scripts in the WordPress editor are not loaded.
     *
     * This function fixes the file path with the expected behavior.
     */
    public static function maybeAdaptGatoGraphQLBundledExtensionPluginFile(
        string $extensionFile,
        string $extensionClass,
        string $extensionPackageOwner
    ): string {
        if (!RootEnvironment::isApplicationEnvironmentDev()) {
            return $extensionFile;
        }
        $extensionManager = PluginApp::getExtensionManager();
        if (!$extensionManager->isExtensionBundled($extensionClass)) {
            return $extensionFile;
        }

        /** @var BundleExtensionInterface */
        $bundlingExtension = $extensionManager->getBundlingExtension($extensionClass);
        $bundlePluginFile = $bundlingExtension->getPluginFile();
        $extensionFileComponents = explode('/', $extensionFile);
        $extensionFileComponentsCount = count($extensionFileComponents);
        $extensionPluginFolderName = $extensionFileComponents[$extensionFileComponentsCount - 2];
        $extensionPluginFileName = $extensionFileComponents[$extensionFileComponentsCount - 1];
        $extensionFile = dirname($bundlePluginFile) . '/vendor/' . $extensionPackageOwner . '/' . $extensionPluginFolderName . '/' . $extensionPluginFileName;
        return $extensionFile;        
    }
}