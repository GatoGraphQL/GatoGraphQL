<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Facades\Settings;

use GatoGraphQL\GatoGraphQL\Settings\OptionNamespacer;
use GatoGraphQL\GatoGraphQL\Settings\OptionNamespacerInterface;

/**
 * Obtain an instance of the OptionNamespacer.
 * Manage the instance internally instead of using the ContainerBuilder,
 * because it is required for setting configuration values before components
 * are initialized, so the ContainerBuilder is still unavailable
 */
class OptionNamespacerFacade
{
    private static ?OptionNamespacerInterface $instance = null;

    public static function getInstance(): OptionNamespacerInterface
    {
        if (self::$instance === null) {
            self::$instance = new OptionNamespacer();
        }
        return self::$instance;
    }
}
