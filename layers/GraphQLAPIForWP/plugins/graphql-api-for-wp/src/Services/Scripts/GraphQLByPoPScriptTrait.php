<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Scripts;

use GraphQLAPI\GraphQLAPI\PluginManagement\MainPluginManager;

/**
 * Trait to set common functions for a Gutenberg block for this plugin (GraphQL API)
 */
trait GraphQLByPoPScriptTrait
{
    protected function getPluginDir(): string
    {
        return (string) MainPluginManager::getConfig('dir');
    }

    protected function getPluginURL(): string
    {
        // Remove the trailing slash
        return trim((string) MainPluginManager::getConfig('url'), '/');
    }
}
