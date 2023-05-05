<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container\CompilerPasses;

use GatoGraphQL\GatoGraphQL\Registries\UserAuthorizationSchemeRegistryInterface;
use GatoGraphQL\GatoGraphQL\Security\UserAuthorizationSchemes\UserAuthorizationSchemeInterface;
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
