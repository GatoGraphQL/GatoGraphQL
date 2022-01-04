<?php

declare(strict_types=1);

namespace PoPSchema\UserMeta;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoPSchema\SchemaCommons\Constants\Behaviors;

class ComponentConfiguration extends \PoP\BasicService\Component\AbstractComponentConfiguration
{
    private static array $getUserMetaEntries = [];
    private static string $getUserMetaBehavior = Behaviors::ALLOWLIST;

    public static function getUserMetaEntries(): array
    {
        // Define properties
        $envVariable = Environment::USER_META_ENTRIES;
        $selfProperty = &self::$getUserMetaEntries;
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

    public static function getUserMetaBehavior(): string
    {
        // Define properties
        $envVariable = Environment::USER_META_BEHAVIOR;
        $selfProperty = &self::$getUserMetaBehavior;
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
