<?php

declare(strict_types=1);

namespace PoPWPSchema\SchemaCommons\StaticHelpers;

use function is_multisite;
use function get_option;
use function wp_get_theme;

class WordPressStaticHelpers
{
    /**
     * @var string[]|null
     */
    private static ?array $activeWordPressPluginFiles = null;
    /**
     * @var string[]|null
     */
    private static ?array $activeWordPressPluginSlugs = null;
    private static ?string $activeWordPressThemeStylesheet = null;
    private static ?string $activeWordPressThemeTemplate = null;

    /**
     * If param $pluginFileOrSlug ends with ".php", then it's the
     * plugin file, otherwise it's the plugin slug.
     */
    public static function isWordPressPluginActive(string $pluginFileOrSlug): bool
    {
        if (str_ends_with($pluginFileOrSlug, '.php')) {
            $pluginFile = $pluginFileOrSlug;
            return in_array($pluginFile, static::getActiveWordPressPluginFiles());
        }
        $pluginSlug = $pluginFileOrSlug;
        return in_array($pluginSlug, static::getActiveWordPressPluginSlugs());
    }

    /**
     * @return string[]
     */
    protected static function getActiveWordPressPluginFiles(): array
    {
        if (self::$activeWordPressPluginFiles === null) {
            $activePlugins = get_option('active_plugins', []);
            self::$activeWordPressPluginFiles = is_array($activePlugins) ? $activePlugins : [];
            if (is_multisite()) {
                /** @var string[] */
                $activeNetworkWordPressPluginFiles = array_keys(get_network_option(0, 'active_sitewide_plugins', []));
                self::$activeWordPressPluginFiles = array_values(array_unique(array_merge(
                    self::$activeWordPressPluginFiles,
                    $activeNetworkWordPressPluginFiles
                )));
            }
        }
        return self::$activeWordPressPluginFiles;
    }

    /**
     * @return string[]
     */
    protected static function getActiveWordPressPluginSlugs(): array
    {
        if (self::$activeWordPressPluginSlugs === null) {
            self::$activeWordPressPluginSlugs = array_map(
                function (string $pluginFile): string {
                    $pos = strpos($pluginFile, '/');
                    if ($pos === false) {
                        return $pluginFile;
                    }
                    return substr($pluginFile, 0, $pos);
                },
                static::getActiveWordPressPluginFiles()
            );
        }
        return self::$activeWordPressPluginSlugs;
    }

    /**
     * Check if a theme is active by its slug or template slug (child theme)
     */
    public static function isWordPressThemeActive(string $themeSlug): bool
    {
        return $themeSlug === static::getActiveWordPressThemeStylesheet() || $themeSlug === static::getActiveWordPressThemeTemplate();
    }

    /**
     * Get the active theme slug
     */
    protected static function getActiveWordPressThemeStylesheet(): string
    {
        if (self::$activeWordPressThemeStylesheet === null) {
            $theme = wp_get_theme();
            self::$activeWordPressThemeStylesheet = $theme->get_stylesheet();
        }
        return self::$activeWordPressThemeStylesheet;
    }

    /**
     * Get the active theme template slug
     */
    protected static function getActiveWordPressThemeTemplate(): string
    {
        if (self::$activeWordPressThemeTemplate === null) {
            $theme = wp_get_theme();
            self::$activeWordPressThemeTemplate = $theme->get_template();
        }
        return self::$activeWordPressThemeTemplate;
    }
}
