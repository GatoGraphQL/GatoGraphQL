<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\ContentProcessors\PluginMarkdownContentRetrieverTrait;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\HasMarkdownDocumentationModuleResolverTrait;

trait ModuleResolverTrait
{
    use HasMarkdownDocumentationModuleResolverTrait;
    use PluginMarkdownContentRetrieverTrait;

    protected function getDefaultValueLabel(): string
    {
        return \__('Default value for the Schema Configuration', 'graphql-api');
    }

    protected function getDefaultValueDescription(): string
    {
        return \__('This value will be used when option <code>"Default"</code> is selected in the Schema Configuration', 'graphql-api');
    }

    protected function getAdminClientDescription(): string
    {
        return \__('It will be applied on the GraphiQL and Interactive Schema clients, configuration in Access/Cache Control Lists, others', 'graphql-api');
    }
}
