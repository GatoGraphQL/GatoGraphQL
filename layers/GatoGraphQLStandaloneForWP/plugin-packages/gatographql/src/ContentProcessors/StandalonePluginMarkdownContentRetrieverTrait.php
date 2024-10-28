<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\ContentProcessors;

use GatoGraphQLStandalone\GatoGraphQL\PluginSkeleton\StandalonePluginTrait;
use GatoGraphQLStandalone\GatoGraphQL\StandalonePluginStaticHelpers;
use GatoGraphQL\GatoGraphQL\ContentProcessors\PluginMarkdownContentRetrieverTrait;

trait StandalonePluginMarkdownContentRetrieverTrait
{
    use PluginMarkdownContentRetrieverTrait {
        getBaseDir as upstreamGetBaseDir;
        getBaseURL as upstreamGetBaseURL;
    }
    use StandalonePluginTrait;

    /**
     * Get the dir where to look for the documentation.
     */
    protected function getBaseDir(): string
    {
        return $this->replaceGatoGraphQLComposerRelativePath(
            $this->upstreamGetBaseDir()
        );
    }

    /**
     * Get the URL where to look for the documentation.
     */
    protected function getBaseURL(): string
    {
        return $this->replaceGatoGraphQLComposerRelativePath(
            $this->upstreamGetBaseURL()
        );
    }

    protected function replaceGatoGraphQLComposerRelativePath(string $string): string
    {
        return str_replace(
            $this->getGatoGraphQLComposerRelativePath(),
            $this->getStandaloneGatoGraphQLComposerRelativePath(),
            $string
        );
    }

    /**
     * Get the GitHub repo URL, to retrieve images for PROD.
     */
    protected function getGitHubRepoDocsRootPathURL(): string
    {
        return StandalonePluginStaticHelpers::getGitHubRepoDocsRootPathURL();
    }
}
