<?php

declare(strict_types=1);

namespace PoPSchema\Media;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;

class ComponentConfiguration extends \PoP\BasicService\Component\AbstractComponentConfiguration
{
    private static ?int $getMediaListDefaultLimit = 10;
    private static ?int $getMediaListMaxLimit = -1;

    public static function getMediaListDefaultLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::MEDIA_LIST_DEFAULT_LIMIT;
        $selfProperty = &self::$getMediaListDefaultLimit;
        $defaultValue = 10;
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public static function getMediaListMaxLimit(): ?int
    {
        // Define properties
        $envVariable = Environment::MEDIA_LIST_MAX_LIMIT;
        $selfProperty = &self::$getMediaListMaxLimit;
        $defaultValue = -1; // Unlimited
        $callback = [EnvironmentValueHelpers::class, 'toInt'];

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
