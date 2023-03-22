<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\PROPluginInformation\Services\Blocks;

use GraphQLAPI\GraphQLAPI\Services\Scripts\MainPluginScriptTrait;

/**
 * Trait to set common functions for a Gutenberg block for this plugin (GraphQL API)
 */
trait PROPluginBlockTrait
{
    use MainPluginScriptTrait;

    protected function getBlockNamespace(): string
    {
        return 'graphql-api';
    }
}
