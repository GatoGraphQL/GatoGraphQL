<?php

declare(strict_types=1);

namespace GraphQLAPI\EventsManager\ModuleResolvers;

use GraphQLAPI\EventsManager\ContentProcessors\PluginMarkdownContentRetrieverTrait;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\HasMarkdownDocumentationModuleResolverTrait;

trait ModuleResolverTrait
{
    use HasMarkdownDocumentationModuleResolverTrait;
    use PluginMarkdownContentRetrieverTrait;
}
