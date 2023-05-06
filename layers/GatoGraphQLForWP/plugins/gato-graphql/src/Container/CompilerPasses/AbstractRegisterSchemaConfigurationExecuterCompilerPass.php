<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container\CompilerPasses;

use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

abstract class AbstractRegisterSchemaConfigurationExecuterCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryMethodCallName(): string
    {
        return 'addSchemaConfigurationExecuter';
    }
}
