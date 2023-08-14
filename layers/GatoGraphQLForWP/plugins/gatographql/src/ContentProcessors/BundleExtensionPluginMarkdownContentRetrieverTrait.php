<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ContentProcessors;

use GatoGraphQL\GatoGraphQL\PluginStaticHelpers;

trait BundleExtensionPluginMarkdownContentRetrieverTrait
{
    use ExtensionPluginMarkdownContentRetrieverTrait;

    /**
     * Get the folder under which the docs are stored
     */
    protected function getDocsFolder(): string
    {
        return 'bundle-extensions';
    }

    protected function getGitHubRepoDocsRootPathURL(): string
    {
        return str_replace(
            '/docs/',
            '/bundle-extensions/',
            PluginStaticHelpers::getGitHubRepoDocsRootPathURL(),
        );
    }
}
