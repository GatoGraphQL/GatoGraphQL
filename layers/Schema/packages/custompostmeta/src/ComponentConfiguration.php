<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMeta;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoPSchema\SchemaCommons\Constants\Behaviors;

class ComponentConfiguration extends \PoP\BasicService\Component\AbstractComponentConfiguration
{
    private static array $getCustomPostMetaEntries = [];
    private static string $getCustomPostMetaBehavior = Behaviors::ALLOWLIST;

    public static function getCustomPostMetaEntries(): array
    {
        // Define properties
        $envVariable = Environment::CUSTOMPOST_META_ENTRIES;
        $selfProperty = &self::$getCustomPostMetaEntries;
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

    public static function getCustomPostMetaBehavior(): string
    {
        // Define properties
        $envVariable = Environment::CUSTOMPOST_META_BEHAVIOR;
        $selfProperty = &self::$getCustomPostMetaBehavior;
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
