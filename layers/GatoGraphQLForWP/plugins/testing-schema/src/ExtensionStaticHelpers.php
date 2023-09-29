<?php

declare(strict_types=1);

namespace GatoGraphQL\TestingSchema;

use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\StaticHelpers\PluginVersionHelpers;

class ExtensionStaticHelpers
{
    public static function getGitHubRepoDocsRootURL(): string
    {
        return sprintf(
            'https://raw.githubusercontent.com/%s/%s',
            ExtensionMetadata::GITHUB_REPO_OWNER,
            ExtensionMetadata::GITHUB_REPO_NAME
        );
    }

    public static function getStablePackageTagForCurrentVersion(): string
    {
        $extensionPluginVersion = PluginApp::getExtension(GatoGraphQLExtension::class)->getPluginVersion();
        return PluginVersionHelpers::isDevelopmentVersion($extensionPluginVersion)
            ? ExtensionMetadata::GIT_BASE_BRANCH
            : $extensionPluginVersion;
    }

    public static function getGitHubRepoDocsRootPathURL(): string
    {
        return static::getGitHubRepoDocsRootURL() . '/' . static::getStablePackageTagForCurrentVersion() . '/layers/GatoGraphQLForWP/plugins/testing-schema/';
    }
}
