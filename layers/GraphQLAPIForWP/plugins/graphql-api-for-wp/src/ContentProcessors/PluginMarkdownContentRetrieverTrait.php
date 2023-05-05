<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ContentProcessors;

use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\PluginStaticHelpers;

trait PluginMarkdownContentRetrieverTrait
{
    /**
     * Get the dir where to look for the documentation.
     */
    protected function getBaseDir(): string
    {
        return PluginApp::getMainPlugin()->getPluginDir();
    }

    /**
     * Get the URL where to look for the documentation.
     */
    protected function getBaseURL(): string
    {
        return PluginApp::getMainPlugin()->getPluginURL();
    }

    /**
     * Get the folder under which the docs are stored
     */
    protected function getDocsFolder(): string
    {
        return 'docs';
    }

    /**
     * Get the GitHub repo URL, to retrieve images for PROD.
     */
    protected function getGitHubRepoDocsRootPathURL(): string
    {
        return PluginStaticHelpers::getGitHubRepoDocsRootPathURL();
    }

    /**
     * Use `false` to pass the "docs" folder when requesting
     * the file to read (so can retrieve files from either
     * "docs" or "docs-pro" folders)
     */
    protected function getUseDocsFolderInFileDir(): bool
    {
        return true;
    }
}
