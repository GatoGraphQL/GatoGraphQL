<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container\HybridCompilerPasses;

use GatoGraphQL\GatoGraphQL\Registries\SettingsCategoryRegistryInterface;
use GatoGraphQL\GatoGraphQL\SettingsCategoryResolvers\SettingsCategoryResolverInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterSettingsCategoryResolverCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return SettingsCategoryRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return SettingsCategoryResolverInterface::class;
    }
    protected function getRegistryMethodCallName(): string
    {
        return 'addSettingsCategoryResolver';
    }
}
