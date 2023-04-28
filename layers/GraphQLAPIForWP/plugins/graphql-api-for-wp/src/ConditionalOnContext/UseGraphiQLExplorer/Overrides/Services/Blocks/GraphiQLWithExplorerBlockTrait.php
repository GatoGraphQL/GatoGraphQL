<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\UseGraphiQLExplorer\Overrides\Services\Blocks;

/**
 * GraphiQL with Explorer block
 */
trait GraphiQLWithExplorerBlockTrait
{
 /**
     * Override the location of the script
     */
    protected function getBlockDirURL(): string
    {
        return $this->getPluginURL() . '/blocks/graphiql-with-explorer/';
    }
}
