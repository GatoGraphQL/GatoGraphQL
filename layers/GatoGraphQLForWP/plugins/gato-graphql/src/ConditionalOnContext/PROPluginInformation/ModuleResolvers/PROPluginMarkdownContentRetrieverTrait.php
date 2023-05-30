<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ConditionalOnContext\PROPluginInformation\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ContentProcessors\PluginMarkdownContentRetrieverTrait;
use GatoGraphQL\GatoGraphQL\PluginStaticHelpers;

trait PROPluginMarkdownContentRetrieverTrait
{
    use PluginMarkdownContentRetrieverTrait;

    /**
     * Get the folder under which the docs are stored
     */
    protected function getDocsFolder(): string
    {
        return 'docs-extensions';
    }

    /**
     * Get the GitHub repo URL, to retrieve images for PROD.
     */
    protected function getGitHubRepoDocsRootPathURL(): string
    {
        return str_replace(
            '/docs/',
            '/docs-extensions/',
            PluginStaticHelpers::getGitHubRepoDocsRootPathURL(),
        );
    }
}
