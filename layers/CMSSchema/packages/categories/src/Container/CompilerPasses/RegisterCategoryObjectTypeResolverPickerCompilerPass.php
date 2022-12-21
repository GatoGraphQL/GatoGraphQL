<?php

declare(strict_types=1);

namespace PoPCMSSchema\Categories\Container\CompilerPasses;

use PoPCMSSchema\Categories\ObjectTypeResolverPickers\CategoryObjectTypeResolverPickerInterface;
use PoPCMSSchema\Categories\Registries\CategoryObjectTypeResolverPickerRegistryInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterCategoryObjectTypeResolverPickerCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return CategoryObjectTypeResolverPickerRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return CategoryObjectTypeResolverPickerInterface::class;
    }
    protected function getRegistryMethodCallName(): string
    {
        return 'addCategoryObjectTypeResolverPicker';
    }
    protected function onlyProcessAutoconfiguredServices(): bool
    {
        return true;
    }
}
