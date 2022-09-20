<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Container\CompilerPasses;

use PoP\ComponentModel\Container\ServiceTags\MandatoryDirectiveServiceTagInterface;
use PoP\ComponentModel\Engine\DataloadingEngineInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterMandatoryDirectiveServiceTagCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return DataloadingEngineInterface::class;
    }
    protected function getServiceClass(): string
    {
        return MandatoryDirectiveServiceTagInterface::class;
    }
    protected function getRegistryMethodCallName(): string
    {
        return 'addMandatoryFieldDirectiveResolver';
    }
}
