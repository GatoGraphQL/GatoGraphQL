<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Facades;

use GatoGraphQL\GatoGraphQL\Settings\UserSettingsManager;
use GatoGraphQL\GatoGraphQL\Settings\UserSettingsManagerInterface;

/**
 * Obtain an instance of the UserSettingsManager.
 * Manage the instance internally instead of using the ContainerBuilder,
 * because it is required for setting configuration values before components
 * are initialized, so the ContainerBuilder is still unavailable
 */
class UserSettingsManagerFacade
{
    private static ?UserSettingsManagerInterface $instance = null;

    public static function getInstance(): UserSettingsManagerInterface
    {
        if (self::$instance === null) {
            self::$instance = new UserSettingsManager();
        }
        return self::$instance;
    }
}
