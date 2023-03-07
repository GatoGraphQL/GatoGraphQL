<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

class PluginStaticHelpers
{
    public static function getGitHubRepoDocsRootPathURL(): string
    {
        $mainPluginVersion = App::getMainPlugin()->getPluginVersion();
        $tag = str_ends_with($mainPluginVersion, '-dev')
            ? 'master'
            : $mainPluginVersion;
        return 'https://raw.githubusercontent.com/leoloso/PoP/' . $tag . '/layers/GraphQLAPIForWP/plugins/graphql-api-for-wp/';
    }
}
