<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\HybridCompilerPasses\CompilerPasses;

use GraphQLAPI\GraphQLAPI\Registries\SettingsCategoryRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\SettingsCategoryResolvers\SettingsCategoryResolverInterface;
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
