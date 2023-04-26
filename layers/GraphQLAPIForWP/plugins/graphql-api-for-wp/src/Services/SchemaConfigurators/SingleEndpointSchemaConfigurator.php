<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\SchemaConfigurators;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;

class SingleEndpointSchemaConfigurator extends AbstractSchemaConfigurationEndpointSchemaConfigurator
{
    protected function getEnablingModule(): string
    {
        return EndpointFunctionalityModuleResolver::SINGLE_ENDPOINT;
    }
}
