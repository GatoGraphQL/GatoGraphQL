<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\CompilerPasses;

use GraphQLAPI\GraphQLAPI\Registries\PersistedQueryEndpointSchemaConfigurationExecuterRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters\PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface;

class RegisterPersistedQueryEndpointSchemaConfigurationExecuterCompilerPass extends AbstractRegisterSchemaConfigurationExecuterCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return PersistedQueryEndpointSchemaConfigurationExecuterRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface::class;
    }
}
