<?php

declare(strict_types=1);

namespace PoPSchema\Settings;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;

class ComponentConfiguration
{
    use ComponentConfigurationTrait;

    private static array $getSettingsEntries = [];
    private static bool $areSettingsEntriesBlacklisted = false;

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

    public static function areSettingsEntriesBlacklisted(): bool
    {
        // Define properties
        $envVariable = Environment::ARE_SETTINGS_ENTRIES_BLACKLISTED;
        $selfProperty = &self::$areSettingsEntriesBlacklisted;
        $defaultValue = false;
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
