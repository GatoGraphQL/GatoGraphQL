<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Container\CompilerPasses;

use GraphQLAPI\GraphQLAPI\Registries\UserAuthorizationSchemeRegistryInterface;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationSchemes\UserAuthorizationSchemeInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterUserAuthorizationSchemeCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return UserAuthorizationSchemeRegistryInterface::class;
    }
    protected function getServiceClass(): string
    {
        return UserAuthorizationSchemeInterface::class;
    }
    protected function getRegistryMethodCallName(): string
    {
        return 'addUserAuthorizationScheme';
    }
}
