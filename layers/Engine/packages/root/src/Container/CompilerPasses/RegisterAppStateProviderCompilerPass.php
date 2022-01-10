<?php

declare(strict_types=1);

namespace PoP\Root\Container\CompilerPasses;

use GraphQLAPI\GraphQLAPI\Registries\AccessControlRuleBlockRegistryInterface;
use PoP\Root\Component\AppStateProviderInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterAppStateProviderCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return AccessControlRuleBlockRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return AppStateProviderInterface::class;
    }
    protected function getRegistryMethodCallName(): string
    {
        return 'addAppStateProvider';
    }
}
