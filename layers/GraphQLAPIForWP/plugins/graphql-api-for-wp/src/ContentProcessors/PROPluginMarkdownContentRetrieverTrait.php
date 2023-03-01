<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ContentProcessors;

use GraphQLAPI\GraphQLAPI\PluginStaticHelpers;

trait PROPluginMarkdownContentRetrieverTrait
{
    use PluginMarkdownContentRetrieverTrait;

    /**
     * Get the folder under which the docs are stored
     */
    protected function getDocsFolder(): string
    {
        return 'docs-pro';
    }

    /**
     * Get the GitHub repo URL, to retrieve images for PROD.
     */
    protected function getGitHubRepoDocsPathURL(): string
    {
        return str_replace(
            '/docs/',
            '/docs-pro/',
            PluginStaticHelpers::getGitHubRepoDocsPathURL(),
        );
    }
}
