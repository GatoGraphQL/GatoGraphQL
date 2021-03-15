<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\CompilerPasses;

use GraphQLAPI\GraphQLAPI\Registries\CustomPostTypeRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\CustomPostTypeInterface;
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
