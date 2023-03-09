<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\HybridCompilerPasses;

use GraphQLAPI\GraphQLAPI\Registries\SettingsCategoryRegistryInterface;
use GraphQLAPI\GraphQLAPI\SettingsCategoryResolvers\SettingsCategoryResolverInterface;
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
