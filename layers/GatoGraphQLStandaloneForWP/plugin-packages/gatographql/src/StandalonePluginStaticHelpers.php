<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL;

use GatoGraphQL\GatoGraphQL\PluginStaticHelpers;

class StandalonePluginStaticHelpers
{
    public static function getGitHubRepoDocsRootPathURL(): string
    {
        return str_replace(
            '/layers/GatoGraphQLForWP/plugins/gatographql',
            '/layers/GatoGraphQLStandaloneForWP/plugin-packages/gatographql',
            PluginStaticHelpers::getGitHubRepoDocsRootPathURL()
        );
    }
}
