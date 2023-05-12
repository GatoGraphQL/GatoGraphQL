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
        return 'extensions/' . $this->getExtensionSubfolder() . '/docs';
    }

    abstract protected function getExtensionSubfolder(): string;

    /**
     * Get the GitHub repo URL, to retrieve images for PROD.
     */
    protected function getGitHubRepoDocsRootPathURL(): string
    {
        return str_replace(
            '/docs/',
            '/extensions/' . $this->getExtensionSubfolder() . '/docs/',
            PluginStaticHelpers::getGitHubRepoDocsRootPathURL(),
        );
    }
}
