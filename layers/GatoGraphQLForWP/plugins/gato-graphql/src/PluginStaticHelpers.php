<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL;

class PluginStaticHelpers
{
    public static function getGitHubRepoDocsRootPathURL(): string
    {
        $mainPluginVersion = PluginApp::getMainPlugin()->getPluginVersion();
        $tag = str_ends_with($mainPluginVersion, '-dev')
            ? 'master'
            : $mainPluginVersion;
        return 'https://raw.githubusercontent.com/leoloso/PoP/' . $tag . '/layers/GatoGraphQLForWP/plugins/gato-graphql/';
    }

    public static function isWordPressPluginActive(string $pluginFile): bool
    {
        $activePlugins = apply_filters('active_plugins', get_option('active_plugins'));
        return in_array($pluginFile, $activePlugins);
    }
}
