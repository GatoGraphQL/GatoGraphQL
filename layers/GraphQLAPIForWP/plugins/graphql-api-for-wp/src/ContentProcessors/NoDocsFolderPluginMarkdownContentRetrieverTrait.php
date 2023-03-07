<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ContentProcessors;

trait NoDocsFolderPluginMarkdownContentRetrieverTrait
{
    use PluginMarkdownContentRetrieverTrait;

    /**
     * Use `false` to pass the "docs" folder when requesting
     * the file to read (so can retrieve files from either
     * "docs" or "docs-pro" folders)
     */
    protected function getUseDocsFolderInFileDir(): bool
    {
        return false;
    }
}
