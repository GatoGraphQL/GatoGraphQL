<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Container\CompilerPasses;

use GatoGraphQL\GatoGraphQL\RESTAPI\Controllers\RESTControllerInterface;
use GatoGraphQL\GatoGraphQL\RESTAPI\Registries\RESTControllerRegistryInterface;
use PoP\Root\Container\CompilerPasses\AbstractInjectServiceIntoRegistryCompilerPass;

class RegisterRESTControllerCompilerPass extends AbstractInjectServiceIntoRegistryCompilerPass
{
    protected function getRegistryServiceDefinition(): string
    {
        return RESTControllerRegistryInterface::class;
    }

    protected function getServiceClass(): string
    {
        return RESTControllerInterface::class;
    }

    protected function getRegistryMethodCallName(): string
    {
        return 'addRESTController';
    }
}
