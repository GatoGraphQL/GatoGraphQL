<?php

declare(strict_types=1);

namespace GraphQLAPI\EventsManager\ModuleResolvers;

use GraphQLAPI\EventsManager\PluginInfo;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\HasMarkdownDocumentationModuleResolverTrait;

trait ModuleResolverTrait
{
    use HasMarkdownDocumentationModuleResolverTrait;

    /**
     * Get the dir where to look for the documentation.
     */
    protected function getBaseDir(): string
    {
        return PluginInfo::get('dir');
    }

    /**
     * Get the URL where to look for the documentation.
     */
    protected function getBaseURL(): string
    {
        return PluginInfo::get('url');
    }
}
