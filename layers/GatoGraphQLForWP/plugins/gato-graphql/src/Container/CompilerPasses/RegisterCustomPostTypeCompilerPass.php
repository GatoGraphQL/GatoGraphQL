<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container\CompilerPasses;

use GatoGraphQL\GatoGraphQL\Registries\CustomPostTypeRegistryInterface;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\CustomPostTypeInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterCustomPostTypeCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return CustomPostTypeRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return CustomPostTypeInterface::class;
    }
    protected function getRegistryMethodCallName(): string
    {
        return 'addCustomPostType';
    }
}
