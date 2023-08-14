<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container\CompilerPasses;

use GatoGraphQL\GatoGraphQL\Registries\PersistedQueryEndpointAnnotatorRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\EndpointAnnotators\PersistedQueryEndpointAnnotatorServiceTagInterface;

class RegisterPersistedQueryEndpointAnnotatorCompilerPass extends AbstractRegisterEndpointAnnotatorCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return PersistedQueryEndpointAnnotatorRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return PersistedQueryEndpointAnnotatorServiceTagInterface::class;
    }
}
