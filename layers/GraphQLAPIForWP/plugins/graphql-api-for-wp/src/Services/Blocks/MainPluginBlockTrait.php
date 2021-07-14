<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\Services\Scripts\MainPluginScriptTrait;

/**
 * Trait to set common functions for a Gutenberg block for this plugin (GraphQL API)
 */
trait MainPluginBlockTrait
{
    use MainPluginScriptTrait;

    protected function getBlockNamespace(): string
    {
        return 'graphql-api';
    }
}
