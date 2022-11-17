<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\Container\CompilerPasses;

use PoPCMSSchema\CustomPosts\ObjectTypeResolverPickers\CustomPostObjectTypeResolverPickerInterface;
use PoPCMSSchema\CustomPosts\Registries\CustomPostObjectTypeResolverPickerRegistryInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterCustomPostObjectTypeResolverPickerCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return CustomPostObjectTypeResolverPickerRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return CustomPostObjectTypeResolverPickerInterface::class;
    }
    protected function getRegistryMethodCallName(): string
    {
        return 'addCustomPostObjectTypeResolverPicker';
    }
    protected function onlyProcessAutoconfiguredServices(): bool
    {
        return true;
    }
}
