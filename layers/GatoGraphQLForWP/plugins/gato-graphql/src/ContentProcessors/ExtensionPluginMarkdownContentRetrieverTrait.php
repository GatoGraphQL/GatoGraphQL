<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ContentProcessors;

use GatoGraphQL\GatoGraphQL\PluginStaticHelpers;

trait ExtensionPluginMarkdownContentRetrieverTrait
{
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
