<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\CompilerPasses;

use GraphQLAPI\GraphQLAPI\Registries\PersistedQuerySchemaConfigurationExecuterRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\SchemaConfigurationExecuters\PersistedQuerySchemaConfigurationExecuterServiceTagInterface;

class RegisterPersistedQuerySchemaConfigurationExecuterCompilerPass extends AbstractRegisterSchemaConfigurationExecuterCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return PersistedQuerySchemaConfigurationExecuterRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return PersistedQuerySchemaConfigurationExecuterServiceTagInterface::class;
    }
}
