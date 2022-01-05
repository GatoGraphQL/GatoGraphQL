<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ContentProcessors;

use GraphQLAPI\GraphQLAPI\App;

trait PluginMarkdownContentRetrieverTrait
{
    /**
     * Get the dir where to look for the documentation.
     */
    protected function getBaseDir(): string
    {
        return App::getMainPlugin()->getPluginDir();
    }

    /**
     * Get the URL where to look for the documentation.
     */
    protected function getBaseURL(): string
    {
        return App::getMainPlugin()->getPluginURL();
    }
}
