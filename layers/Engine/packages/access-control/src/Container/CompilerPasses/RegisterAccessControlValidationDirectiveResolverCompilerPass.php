<?php

declare(strict_types=1);

namespace PoP\AccessControl\Container\CompilerPasses;

use PoP\AccessControl\Container\ServiceTags\AccessControlValidationDirectiveResolverInterface;
use PoP\AccessControl\Registries\AccessControlValidationDirectiveResolverRegistryInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterAccessControlValidationDirectiveResolverCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return AccessControlValidationDirectiveResolverRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return AccessControlValidationDirectiveResolverInterface::class;
    }
    protected function getRegistryMethodCallName(): string
    {
        return 'addAccessControlValidationDirectiveResolver';
    }
}
