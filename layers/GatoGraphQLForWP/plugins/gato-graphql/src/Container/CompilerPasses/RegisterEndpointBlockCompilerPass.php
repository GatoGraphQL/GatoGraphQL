<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container\CompilerPasses;

use GatoGraphQL\GatoGraphQL\Registries\EndpointBlockRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\Blocks\EndpointEditorBlockServiceTagInterface;

class RegisterEndpointBlockCompilerPass extends AbstractRegisterEditorBlockCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return EndpointBlockRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return EndpointEditorBlockServiceTagInterface::class;
    }
}
