<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Facades;

use GatoGraphQL\GatoGraphQL\Settings\TransientSettingsManager;
use GatoGraphQL\GatoGraphQL\Settings\TransientSettingsManagerInterface;

/**
 * Obtain an instance of the TransientSettingsManager.
 * Manage the instance internally instead of using the ContainerBuilder,
 * because it is required for setting configuration values before components
 * are initialized, so the ContainerBuilder is still unavailable
 */
class TransientSettingsManagerFacade
{
    private static ?TransientSettingsManagerInterface $instance = null;

    public static function getInstance(): TransientSettingsManagerInterface
    {
        if (self::$instance === null) {
            self::$instance = new TransientSettingsManager();
        }
        return self::$instance;
    }
}
