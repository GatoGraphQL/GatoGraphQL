<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Facades\Registries;

use PoP\Root\App;
use GatoGraphQL\GatoGraphQL\Registries\SettingsCategoryRegistryInterface;

/**
 * Obtain an instance of the SettingsCategoryRegistry.
 */
class SettingsCategoryRegistryFacade
{
    public static function getInstance(): SettingsCategoryRegistryInterface
    {
        /**
         * @var SettingsCategoryRegistryInterface
         */
        return App::getContainer()->get(SettingsCategoryRegistryInterface::class);
    }
}
