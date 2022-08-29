<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

trait CommonModuleResolverTrait
{
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
        return \__('It will be applied on the GraphiQL and Interactive Schema clients', 'graphql-api');
    }

    protected function getAdminClientAndConfigurationDescription(): string
    {
        return \__('It will be applied on the GraphiQL and Interactive Schema clients, and configuration in extensions', 'graphql-api');
    }
}
