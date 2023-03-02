<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers\PRO;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\CommonModuleResolverTrait;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\HasMarkdownDocumentationModuleResolverTrait;
use GraphQLAPI\GraphQLAPI\ContentProcessors\PROPluginMarkdownContentRetrieverTrait;

trait ModuleResolverTrait
{
    use HasMarkdownDocumentationModuleResolverTrait;
    use PROPluginMarkdownContentRetrieverTrait;
    use CommonModuleResolverTrait;
}
