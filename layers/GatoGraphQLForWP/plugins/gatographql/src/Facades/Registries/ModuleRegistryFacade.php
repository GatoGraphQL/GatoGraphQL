<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Facades\Registries;

use PoP\Root\App;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;

/**
 * Obtain an instance of the ModuleRegistry.
 */
class ModuleRegistryFacade
{
    public static function getInstance(): ModuleRegistryInterface
    {
        /**
         * @var ModuleRegistryInterface
         */
        return App::getContainer()->get(ModuleRegistryInterface::class);
    }
}
