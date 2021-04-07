<?php

declare(strict_types=1);

namespace PoPSchema\CDNDirective;

use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;

class ComponentConfiguration
{
    use ComponentConfigurationTrait;

    private static ?string $getFromURLSection = null;
    private static ?string $getToURLSection = null;

    public static function getFromURLSection(): ?string
    {
        // Define properties
        $envVariable = Environment::CDN_REPLACE_FROM_URL_SECTION;
        $selfProperty = &self::$getFromURLSection;

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty
        );
        return $selfProperty;
    }

    public static function getToURLSection(): ?string
    {
        // Define properties
        $envVariable = Environment::CDN_REPLACE_TO_URL_SECTION;
        $selfProperty = &self::$getToURLSection;

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty
        );
        return $selfProperty;
    }
}
