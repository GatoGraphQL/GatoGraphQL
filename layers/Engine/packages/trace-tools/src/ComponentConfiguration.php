<?php

declare(strict_types=1);

namespace PoP\TraceTools;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;

class ComponentConfiguration
{
    use ComponentConfigurationTrait;

    private static bool $sendTracesToLog = true;

    public static function sendTracesToLog(): bool
    {
        // Define properties
        $envVariable = Environment::SEND_TRACES_TO_LOG;
        $selfProperty = &self::$sendTracesToLog;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }
}
