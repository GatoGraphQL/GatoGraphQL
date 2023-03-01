<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ContentProcessors;

trait PROPluginMarkdownContentRetrieverTrait
{
    use PluginMarkdownContentRetrieverTrait;

    /**
     * Get the folder under which the docs are stored
     */
    protected function getDocsFolder(): string
    {
        return 'docs-pro';
    }
}
