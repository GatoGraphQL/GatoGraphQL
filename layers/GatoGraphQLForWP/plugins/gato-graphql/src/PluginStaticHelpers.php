<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL;

use function apply_filters;

class PluginStaticHelpers
{
    /**
     * @var string[]|null
     */
    private static ?array $activeWordPressPluginSlugs = null;

    public static function getGitHubRepoDocsRootPathURL(): string
    {
        $mainPluginVersion = PluginApp::getMainPlugin()->getPluginVersion();
        $tag = str_ends_with($mainPluginVersion, '-dev')
            ? 'master'
            : $mainPluginVersion;
        return 'https://raw.githubusercontent.com/leoloso/PoP/' . $tag . '/layers/GatoGraphQLForWP/plugins/gato-graphql/';
    }

    public static function isWordPressPluginActive(string $pluginSlug): bool
    {
        if (self::$activeWordPressPluginSlugs === null) {
            $activeWordPressPluginFiles = apply_filters('active_plugins', get_option('active_plugins', []));
            self::$activeWordPressPluginSlugs = array_map(
                function (string $pluginFile): string
                {
                    $pos = strpos($pluginFile, '/');
                    if ($pos === false) {
                        return $pluginFile;
                    }
                    return substr($pluginFile, 0, $pos + 1);
                },
                $activeWordPressPluginFiles
            );
        }
        return in_array($pluginSlug, self::$activeWordPressPluginSlugs);
    }
}
