<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container\CompilerPasses;

use GatoGraphQL\GatoGraphQL\Registries\CustomEndpointAnnotatorRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\EndpointAnnotators\CustomEndpointAnnotatorServiceTagInterface;

class RegisterCustomEndpointAnnotatorCompilerPass extends AbstractRegisterEndpointAnnotatorCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return CustomEndpointAnnotatorRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return CustomEndpointAnnotatorServiceTagInterface::class;
    }
}
