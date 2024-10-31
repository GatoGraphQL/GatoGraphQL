<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Facades;

use GatoGraphQL\GatoGraphQL\Settings\TimestampSettingsManager;
use GatoGraphQL\GatoGraphQL\Settings\TimestampSettingsManagerInterface;

/**
 * Obtain an instance of the TimestampSettingsManager.
 * Manage the instance internally instead of using the ContainerBuilder,
 * because it is required for setting configuration values before components
 * are initialized, so the ContainerBuilder is still unavailable
 */
class TimestampSettingsManagerFacade
{
    private static ?TimestampSettingsManagerInterface $instance = null;

    public static function getInstance(): TimestampSettingsManagerInterface
    {
        if (self::$instance === null) {
            self::$instance = new TimestampSettingsManager();
        }
        return self::$instance;
    }
}
