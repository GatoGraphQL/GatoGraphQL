<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Scripts;

/**
 * Trait to set common functions for a Gutenberg block for this plugin (GraphQL API)
 */
trait GraphQLByPoPScriptTrait
{
    protected function getPluginDir(): string
    {
        return \GRAPHQL_API_DIR;
    }

    protected function getPluginURL(): string
    {
        // Remove the trailing slash
        return trim(\GRAPHQL_API_URL, '/');
    }
}
