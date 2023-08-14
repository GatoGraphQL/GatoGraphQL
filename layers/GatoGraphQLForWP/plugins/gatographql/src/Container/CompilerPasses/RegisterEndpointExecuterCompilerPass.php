<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container\CompilerPasses;

use GatoGraphQL\GatoGraphQL\Registries\EndpointExecuterRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\EndpointExecuters\EndpointExecuterServiceTagInterface;

class RegisterEndpointExecuterCompilerPass extends AbstractRegisterEndpointExecuterCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return EndpointExecuterRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return EndpointExecuterServiceTagInterface::class;
    }
}
