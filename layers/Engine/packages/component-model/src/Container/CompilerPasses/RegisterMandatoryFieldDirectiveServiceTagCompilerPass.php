<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\ComponentModel\Container\ServiceTags\MandatoryFieldDirectiveServiceTagInterface;
use PoP\ComponentModel\Registries\MandatoryFieldDirectiveResolverRegistryInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterMandatoryFieldDirectiveServiceTagCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return MandatoryFieldDirectiveResolverRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return MandatoryFieldDirectiveServiceTagInterface::class;
    }
    protected function getRegistryMethodCallName(): string
    {
        return 'addMandatoryFieldDirectiveResolver';
    }
}
