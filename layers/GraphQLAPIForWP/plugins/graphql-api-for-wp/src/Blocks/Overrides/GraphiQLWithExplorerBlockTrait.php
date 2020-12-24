<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Blocks\Overrides;

/**
 * GraphiQL with Explorer block
 */
trait GraphiQLWithExplorerBlockTrait
{
 /**
     * Override the location of the script
     *
     * @return string
     */
    protected function getBlockDirURL(): string
    {
        return $this->getPluginURL() . '/blocks/graphiql-with-explorer/';
    }
}
