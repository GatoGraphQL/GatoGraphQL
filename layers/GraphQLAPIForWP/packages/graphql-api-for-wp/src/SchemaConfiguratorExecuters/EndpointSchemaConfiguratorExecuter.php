<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLEndpointPostType;
use GraphQLAPI\GraphQLAPI\SchemaConfigurators\EndpointSchemaConfigurator;
use GraphQLAPI\GraphQLAPI\SchemaConfigurators\SchemaConfiguratorInterface;

class EndpointSchemaConfiguratorExecuter extends AbstractLoadingCPTSchemaConfiguratorExecuter
{
    protected function getPostType(): string
    {
        return GraphQLEndpointPostType::POST_TYPE;
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return new EndpointSchemaConfigurator();
    }
}
