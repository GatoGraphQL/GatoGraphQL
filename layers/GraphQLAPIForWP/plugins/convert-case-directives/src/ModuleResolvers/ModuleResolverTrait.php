<?php

declare(strict_types=1);

namespace GraphQLAPI\ConvertCaseDirectives\ModuleResolvers;

use GraphQLAPI\ConvertCaseDirectives\ContentProcessors\PluginMarkdownContentRetrieverTrait;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\HasMarkdownDocumentationModuleResolverTrait;

trait ModuleResolverTrait
{
    use HasMarkdownDocumentationModuleResolverTrait;
    use PluginMarkdownContentRetrieverTrait;
}
