<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ContentProcessors;

use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\PluginSkeleton\ExtensionInterface;

trait ExtensionMarkdownContentRetrieverTrait
{
    /**
     * @phpstan-return class-string<ExtensionInterface>
     */
    abstract protected function getExtensionClass(): string;

    /**
     * Get the dir where to look for the documentation.
     */
    protected function getBaseDir(): string
    {
        return PluginApp::getExtension($this->getExtensionClass())->getPluginDir();
    }

    /**
     * Get the URL where to look for the documentation.
     */
    protected function getBaseURL(): string
    {
        return PluginApp::getExtension($this->getExtensionClass())->getPluginURL();
    }

    /**
     * Get the URL where to look for the documentation.
     */
    protected function getDocsFolder(): string
    {
        return 'docs';
    }

    /**
     * Use `false` to pass the "docs" folder when requesting
     * the file to read (so can retrieve files from either
     * "docs" or "docs-extensions" folders)
     */
    protected function getUseDocsFolderInFileDir(): bool
    {
        return true;
    }
}
