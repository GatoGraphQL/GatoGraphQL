<?php

declare(strict_types=1);

namespace GatoGraphQL\TestingSchema;

use GatoGraphQL\GatoGraphQL\PluginApp;

class ExtensionStaticHelpers
{
    public static function getGitHubRepoDocsRootPathURL(): string
    {
        $extensionPluginVersion = PluginApp::getExtension(GatoGraphQLExtension::class)->getPluginVersion();
        $tag = str_ends_with($extensionPluginVersion, '-dev')
            ? 'master'
            : $extensionPluginVersion;
        return 'https://raw.githubusercontent.com/leoloso/PoP/' . $tag . '/layers/GatoGraphQLForWP/plugins/testing-schema/';
    }
}
