<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfiguratorExecuters;

use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLEndpointPostType;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\EndpointSchemaConfigurator;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators\SchemaConfiguratorInterface;

class EndpointSchemaConfiguratorExecuter extends AbstractLoadingCPTSchemaConfiguratorExecuter
{
    protected EndpointSchemaConfigurator $endpointSchemaConfigurator;

    function __construct(
        EndpointSchemaConfigurator $endpointSchemaConfigurator
    ) {
        $this->endpointSchemaConfigurator = $endpointSchemaConfigurator;
    }

    protected function getPostType(): string
    {
        return GraphQLEndpointPostType::POST_TYPE;
    }

    protected function getSchemaConfigurator(): SchemaConfiguratorInterface
    {
        return $this->endpointSchemaConfigurator;
    }
}
