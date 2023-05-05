<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Facades\Registries;

use PoP\Root\App;
use GatoGraphQL\GatoGraphQL\Registries\ModuleTypeRegistryInterface;

/**
 * Obtain an instance of the ModuleTypeRegistry
 */
class ModuleTypeRegistryFacade
{
    public static function getInstance(): ModuleTypeRegistryInterface
    {
        /**
         * @var ModuleTypeRegistryInterface
         */
        return App::getContainer()->get(ModuleTypeRegistryInterface::class);
    }
}
