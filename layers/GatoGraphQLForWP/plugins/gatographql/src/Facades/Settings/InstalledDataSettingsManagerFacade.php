<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Facades\Settings;

use GatoGraphQL\GatoGraphQL\Settings\InstalledDataSettingsManager;
use GatoGraphQL\GatoGraphQL\Settings\InstalledDataSettingsManagerInterface;

/**
 * Obtain an instance of the InstalledDataSettingsManager.
 * Manage the instance internally instead of using the ContainerBuilder,
 * because the plugin installs its data while the plugin is being
 * initialized, so the ContainerBuilder is still unavailable
 */
class InstalledDataSettingsManagerFacade
{
    private static ?InstalledDataSettingsManagerInterface $instance = null;

    public static function getInstance(): InstalledDataSettingsManagerInterface
    {
        if (self::$instance === null) {
            self::$instance = new InstalledDataSettingsManager();
        }
        return self::$instance;
    }
}
