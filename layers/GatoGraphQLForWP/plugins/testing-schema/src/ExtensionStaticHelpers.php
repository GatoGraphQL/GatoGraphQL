<?php

declare(strict_types=1);

namespace GatoGraphQL\TestingSchema;

use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\StaticHelpers\PluginVersionHelpers;

class ExtensionStaticHelpers
{
    private static function getGitHubRepoDocsRootURL(): string
    {
        return sprintf(
            'https://raw.githubusercontent.com/%s/%s',
            ExtensionMetadata::DOCS_GITHUB_REPO_OWNER,
            ExtensionMetadata::DOCS_GITHUB_REPO_NAME
        );
    }

    private static function getGitHubRepoDocsBranchOrTag(): string
    {
        $extensionPluginVersion = PluginApp::getExtension(GatoGraphQLExtension::class)->getPluginVersion();
        return PluginVersionHelpers::isDevelopmentVersion($extensionPluginVersion)
            ? ExtensionMetadata::DOCS_GIT_BASE_BRANCH
            : $extensionPluginVersion;
    }

    public static function getGitHubRepoDocsRootPathURL(): string
    {
        return self::getGitHubRepoDocsRootURL() . '/' . self::getGitHubRepoDocsBranchOrTag() . '/layers/GatoGraphQLForWP/plugins/testing-schema';
    }
}
