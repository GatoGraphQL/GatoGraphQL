<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\PROPluginPseudoModules\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\CommonModuleResolverTrait;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\HasMarkdownDocumentationModuleResolverTrait;
use GraphQLAPI\GraphQLAPI\ContentProcessors\PROPluginMarkdownContentRetrieverTrait;

trait ModuleResolverTrait
{
    use HasMarkdownDocumentationModuleResolverTrait;
    use PROPluginMarkdownContentRetrieverTrait;
    use CommonModuleResolverTrait;
}
