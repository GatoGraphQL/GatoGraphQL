<?php

declare(strict_types=1);

namespace PoPIncludes\GatoGraphQL;

use GatoGraphQL\GatoGraphQL\PluginApp;
use PoP\Root\Environment as RootEnvironment;

use function wp_convert_hr_to_bytes;
use function add_action;
use function add_filter;

class Startup {
    /**
     * Validate that there is enough memory to run the plugin.
     *
     * > Note that to have no memory limit, set this directive to -1.
     *
     * @see https://www.php.net/manual/en/ini.core.php#ini.sect.resource-limits
     */
    public static function checkGatoGraphQLMemoryRequirements(
        string $pluginName,
        string $minRequiredPHPMemoryLimit = '64M',
        ?string $url = null,
    ): bool {
        $phpMemoryLimit = \ini_get('memory_limit');
        $phpMemoryLimitInBytes = wp_convert_hr_to_bytes($phpMemoryLimit);
        if ($phpMemoryLimitInBytes !== -1) {
            $minRequiredPHPMemoryLimitInBytes = wp_convert_hr_to_bytes($minRequiredPHPMemoryLimit);
            if ($phpMemoryLimitInBytes < $minRequiredPHPMemoryLimitInBytes) {
                add_action('admin_notices', function () use ($minRequiredPHPMemoryLimit, $phpMemoryLimit, $pluginName, $url) {
                    printf(
                        '<div class="notice notice-error"><p>%s%s</p></div>',
                        sprintf(
                            __('Plugin <strong>%1$s</strong> requires at least <strong>%2$s</strong> of memory, however the server\'s PHP memory limit is set to <strong>%3$s</strong>. Please increase the memory limit to load the plugin.', 'gatographql'),
                            $pluginName,
                            $minRequiredPHPMemoryLimit,
                            $phpMemoryLimit
                        ),
                        $url ? sprintf(
                            __(' <a href="%s" target="_blank">%s</a>', 'gatographql'),
                            $url,
                            __('Browse documentation&#x2197;', 'gatographql')
                        ) : ''
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

    /**
     * Load the .l10n.php for the current locale into the 'gatographql' text domain,
     * falling back to a shipped variant of the same base language when the exact
     * locale's file is absent (e.g. es_AR / es_MX reuse es_ES, fr_CA reuses fr_FR).
     */
    public static function loadTextdomainWithFallback(string $dir, string $prefix): void
    {
        $locale = determine_locale();
        $translationFile = $dir . $prefix . $locale . '.l10n.php';
        if (!is_readable($translationFile)) {
            $base = (string) strtok($locale, '_');
            $canonical = $dir . $prefix . $base . '_' . strtoupper($base) . '.l10n.php';
            if (is_readable($canonical)) {
                $translationFile = $canonical;
            } else {
                $variants = glob($dir . $prefix . $base . '_*.l10n.php') ?: [];
                $translationFile = $variants[0] ?? $translationFile;
            }
        }
        if (is_readable($translationFile)) {
            load_textdomain('gatographql', $translationFile);
        }
    }

    /**
     * Register the JS translation-pack resolver (same-base-language locale fallback)
     * on the 'gatographql' domain. It is global, so registering it once (by the main
     * plugin, or a self-contained plugin) covers every plugin's scripts.
     */
    public static function registerScriptTranslationFileResolver(): void
    {
        add_filter('load_script_translation_file', [self::class, 'resolveScriptTranslationFile'], 10, 3);
    }

    /**
     * Mirror the .mo language fallback for JS translation packs: when the exact
     * locale's <domain>-<locale>-<md5>.json is missing, reuse a shipped variant of
     * the same base language. The md5 (script-path hash) is locale-independent, so
     * only the locale segment is swapped. Hooked on 'load_script_translation_file'.
     *
     * @param string|false $file
     * @return string|false
     */
    public static function resolveScriptTranslationFile($file, string $handle, string $domain)
    {
        if ($domain !== 'gatographql' || !is_string($file) || is_readable($file)) {
            return $file;
        }
        if (!preg_match('#^(.*/gatographql-)([a-z]{2,3})(?:_[A-Za-z0-9]+)*(-[0-9a-f]+\.json)$#', $file, $m)) {
            return $file;
        }
        $canonical = $m[1] . $m[2] . '_' . strtoupper($m[2]) . $m[3];
        if (is_readable($canonical)) {
            return $canonical;
        }
        $variants = glob($m[1] . $m[2] . '_*' . $m[3]) ?: [];
        return $variants[0] ?? $file;
    }
}