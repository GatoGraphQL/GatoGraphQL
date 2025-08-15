<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Facades;

use GatoGraphQL\GatoGraphQL\Settings\JSONDataOptionSettingsManager;
use GatoGraphQL\GatoGraphQL\Settings\JSONDataOptionSettingsManagerInterface;

/**
 * Obtain an instance of the JSONDataOptionSettingsManager.
 * Manage the instance internally instead of using the ContainerBuilder,
 * because it is required for setting configuration values before components
 * are initialized, so the ContainerBuilder is still unavailable
 */
class JSONDataOptionSettingsManagerFacade
{
    private static ?JSONDataOptionSettingsManagerInterface $instance = null;

    public static function getInstance(): JSONDataOptionSettingsManagerInterface
    {
        if (self::$instance === null) {
            self::$instance = new JSONDataOptionSettingsManager();
        }
        return self::$instance;
    }
}
