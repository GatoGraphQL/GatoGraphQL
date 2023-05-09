<?php

declare(strict_types=1);

namespace GatoGraphQL\TestingSchema\ModuleResolvers;

use GatoGraphQL\TestingSchema\ContentProcessors\ExtensionMarkdownContentRetrieverTrait;
use GatoGraphQL\TestingSchema\ExtensionStaticHelpers;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\CommonModuleResolverTrait;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\HasMarkdownDocumentationModuleResolverTrait;

trait ModuleResolverTrait
{
    use HasMarkdownDocumentationModuleResolverTrait;
    use ExtensionMarkdownContentRetrieverTrait;
    use CommonModuleResolverTrait;

    /**
     * Get the GitHub repo URL, to retrieve images for PROD.
     */
    protected function getGitHubRepoDocsRootPathURL(): string
    {
        return ExtensionStaticHelpers::getGitHubRepoDocsRootPathURL();
    }
}
