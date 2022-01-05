<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ContentProcessors;

use GraphQLAPI\GraphQLAPI\App;

trait ExtensionMarkdownContentRetrieverTrait
{
    abstract protected function getExtensionClass(): string;

    /**
     * Get the dir where to look for the documentation.
     */
    protected function getBaseDir(): string
    {
        return (string) App::getExtensionManager()->getConfig(
            $this->getExtensionClass(),
            'dir'
        );
    }

    /**
     * Get the URL where to look for the documentation.
     */
    protected function getBaseURL(): string
    {
        return (string) App::getExtensionManager()->getConfig(
            $this->getExtensionClass(),
            'url'
        );
    }
}
