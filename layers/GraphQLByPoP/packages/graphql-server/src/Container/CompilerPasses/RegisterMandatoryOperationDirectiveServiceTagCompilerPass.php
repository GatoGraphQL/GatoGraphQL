<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Container\CompilerPasses;

use GraphQLByPoP\GraphQLServer\Container\ServiceTags\MandatoryOperationDirectiveServiceTagInterface;
use GraphQLByPoP\GraphQLServer\Registries\MandatoryOperationDirectiveResolverRegistryInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterMandatoryOperationDirectiveServiceTagCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return MandatoryOperationDirectiveResolverRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return MandatoryOperationDirectiveServiceTagInterface::class;
    }
    protected function getRegistryMethodCallName(): string
    {
        return 'addMandatoryOperationDirectiveResolver';
    }
}
