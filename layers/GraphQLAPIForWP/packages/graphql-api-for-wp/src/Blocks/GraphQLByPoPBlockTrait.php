<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Blocks;

use GraphQLAPI\GraphQLAPI\Scripts\GraphQLByPoPScriptTrait;

/**
 * Trait to set common functions for a Gutenberg block for this plugin (GraphQL API)
 */
trait GraphQLByPoPBlockTrait
{
    use GraphQLByPoPScriptTrait;

    protected function getBlockNamespace(): string
    {
        return 'graphql-api';
    }
}
