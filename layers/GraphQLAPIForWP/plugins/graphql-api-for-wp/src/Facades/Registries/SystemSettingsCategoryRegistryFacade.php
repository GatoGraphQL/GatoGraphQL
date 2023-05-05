<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Facades\Registries;

use PoP\Root\App;
use GatoGraphQL\GatoGraphQL\Registries\SettingsCategoryRegistryInterface;

/**
 * Obtain an instance of the SettingsCategoryRegistry.
 * Use the System Container because it is required for
 * setting configuration values before components
 * are initialized, so the ContainerBuilder is still unavailable
 */
class SystemSettingsCategoryRegistryFacade
{
    public static function getInstance(): SettingsCategoryRegistryInterface
    {
        /**
         * @var SettingsCategoryRegistryInterface
         */
        return App::getSystemContainer()->get(SettingsCategoryRegistryInterface::class);
    }
}
