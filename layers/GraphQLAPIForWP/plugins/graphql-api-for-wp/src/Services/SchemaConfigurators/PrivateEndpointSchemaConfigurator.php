<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;

class PrivateEndpointSchemaConfigurator extends AbstractSchemaConfigurationEndpointSchemaConfigurator
{
    protected function getEnablingModule(): string
    {
        return EndpointFunctionalityModuleResolver::PRIVATE_ENDPOINT;
    }
}
