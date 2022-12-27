<?php

declare(strict_types=1);

namespace PoPCMSSchema\Tags\Container\CompilerPasses;

use PoPCMSSchema\Tags\ObjectTypeResolverPickers\TagObjectTypeResolverPickerInterface;
use PoPCMSSchema\Tags\Registries\TagObjectTypeResolverPickerRegistryInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterTagObjectTypeResolverPickerCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return TagObjectTypeResolverPickerRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return TagObjectTypeResolverPickerInterface::class;
    }
    protected function getRegistryMethodCallName(): string
    {
        return 'addTagObjectTypeResolverPicker';
    }
    protected function onlyProcessAutoconfiguredServices(): bool
    {
        return true;
    }
}
