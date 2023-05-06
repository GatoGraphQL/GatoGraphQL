<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container\CompilerPasses;

use GatoGraphQL\GatoGraphQL\Registries\SchemaConfigBlockRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\Blocks\SchemaConfigEditorBlockServiceTagInterface;

class RegisterSchemaConfigBlockCompilerPass extends AbstractRegisterEditorBlockCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return SchemaConfigBlockRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return SchemaConfigEditorBlockServiceTagInterface::class;
    }
}
