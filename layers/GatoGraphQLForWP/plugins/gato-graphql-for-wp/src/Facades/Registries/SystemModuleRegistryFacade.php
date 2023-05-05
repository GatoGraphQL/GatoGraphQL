<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Facades\Registries;

use PoP\Root\App;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;

/**
 * Obtain an instance of the ModuleRegistry.
 * Use the System Container because it is required for
 * setting configuration values before components
 * are initialized, so the ContainerBuilder is still unavailable
 */
class SystemModuleRegistryFacade
{
    public static function getInstance(): ModuleRegistryInterface
    {
        /**
         * @var ModuleRegistryInterface
         */
        return App::getSystemContainer()->get(ModuleRegistryInterface::class);
    }
}
