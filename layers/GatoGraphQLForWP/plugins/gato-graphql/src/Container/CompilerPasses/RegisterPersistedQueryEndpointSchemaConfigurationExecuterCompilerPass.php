<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container\CompilerPasses;

use GatoGraphQL\GatoGraphQL\Registries\PersistedQueryEndpointSchemaConfigurationExecuterRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters\PersistedQueryEndpointSchemaConfigurationExecuterServiceTagInterface;

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
