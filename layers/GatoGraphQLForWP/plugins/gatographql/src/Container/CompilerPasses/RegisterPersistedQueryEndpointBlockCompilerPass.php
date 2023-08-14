<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container\CompilerPasses;

use GatoGraphQL\GatoGraphQL\Registries\PersistedQueryEndpointBlockRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\Blocks\PersistedQueryEndpointEditorBlockServiceTagInterface;

class RegisterPersistedQueryEndpointBlockCompilerPass extends AbstractRegisterEditorBlockCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return PersistedQueryEndpointBlockRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return PersistedQueryEndpointEditorBlockServiceTagInterface::class;
    }
}
