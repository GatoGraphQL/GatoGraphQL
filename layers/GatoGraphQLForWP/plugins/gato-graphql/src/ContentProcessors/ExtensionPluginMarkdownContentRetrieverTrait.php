<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ContentProcessors;

use GatoGraphQL\GatoGraphQL\PluginStaticHelpers;

trait ExtensionPluginMarkdownContentRetrieverTrait
{
    use PluginMarkdownContentRetrieverTrait;

    /**
     * Get the folder under which the docs are stored
     */
    protected function getDocsFolder(): string
    {
        return 'extensions';
    }

    protected function getGitHubRepoDocsRootPathURL(): string
    {
        return str_replace(
            '/docs/',
            '/extensions/',
            PluginStaticHelpers::getGitHubRepoDocsRootPathURL(),
        );
    }
}
