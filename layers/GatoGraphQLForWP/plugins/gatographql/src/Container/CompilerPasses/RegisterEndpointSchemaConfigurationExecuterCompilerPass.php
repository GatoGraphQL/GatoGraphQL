<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container\CompilerPasses;

use GatoGraphQL\GatoGraphQL\Registries\EndpointSchemaConfigurationExecuterRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\SchemaConfigurationExecuters\EndpointSchemaConfigurationExecuterServiceTagInterface;

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
