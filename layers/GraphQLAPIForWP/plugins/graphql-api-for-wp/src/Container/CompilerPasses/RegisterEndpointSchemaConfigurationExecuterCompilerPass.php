<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\CompilerPasses;

use GraphQLAPI\GraphQLAPI\Registries\EndpointSchemaConfigurationExecuterRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters\EndpointSchemaConfigurationExecuterServiceTagInterface;

class RegisterEndpointSchemaConfigurationExecuterCompilerPass extends AbstractRegisterSchemaConfigurationExecuterCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return EndpointSchemaConfigurationExecuterRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return EndpointSchemaConfigurationExecuterServiceTagInterface::class;
    }
}
