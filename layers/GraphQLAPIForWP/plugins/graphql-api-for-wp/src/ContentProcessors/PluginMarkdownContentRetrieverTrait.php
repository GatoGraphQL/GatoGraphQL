<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ContentProcessors;

use GraphQLAPI\GraphQLAPI\App;
use GraphQLAPI\GraphQLAPI\PluginStaticHelpers;

trait PluginMarkdownContentRetrieverTrait
{
    /**
     * Get the dir where to look for the documentation.
     */
    protected function getBaseDir(): string
    {
        return App::getMainPlugin()->getPluginDir();
    }

    /**
     * Get the URL where to look for the documentation.
     */
    protected function getBaseURL(): string
    {
        return App::getMainPlugin()->getPluginURL();
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
