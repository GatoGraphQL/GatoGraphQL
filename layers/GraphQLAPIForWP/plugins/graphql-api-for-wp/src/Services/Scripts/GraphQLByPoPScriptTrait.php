<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Scripts;

use GraphQLAPI\GraphQLAPI\PluginInfo;

/**
 * Trait to set common functions for a Gutenberg block for this plugin (GraphQL API)
 */
trait GraphQLByPoPScriptTrait
{
    protected function getPluginDir(): string
    {
        return PluginInfo::get('dir');
    }

    protected function getPluginURL(): string
    {
        // Remove the trailing slash
        return trim(PluginInfo::get('url'), '/');
    }
}
