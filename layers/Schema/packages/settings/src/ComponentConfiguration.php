<?php

declare(strict_types=1);

namespace PoPSchema\Settings;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoPSchema\SchemaCommons\Constants\Behaviors;

class ComponentConfiguration extends \PoP\BasicService\Component\AbstractComponentConfiguration
{
    private static array $getSettingsEntries = [];
    private static string $getSettingsBehavior = Behaviors::ALLOWLIST;

    public static function getSettingsEntries(): array
    {
        // Define properties
        $envVariable = Environment::SETTINGS_ENTRIES;
        $selfProperty = &self::$getSettingsEntries;
        $defaultValue = [];
        $callback = [EnvironmentValueHelpers::class, 'commaSeparatedStringToArray'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public static function getSettingsBehavior(): string
    {
        // Define properties
        $envVariable = Environment::SETTINGS_BEHAVIOR;
        $selfProperty = &self::$getSettingsBehavior;
        $defaultValue = Behaviors::ALLOWLIST;

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue
        );
        return $selfProperty;
    }
}
