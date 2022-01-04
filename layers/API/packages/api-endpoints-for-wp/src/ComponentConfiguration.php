<?php

declare(strict_types=1);

namespace PoP\APIEndpointsForWP;

use PoP\APIEndpoints\EndpointUtils;
use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;

class ComponentConfiguration extends \PoP\BasicService\Component\AbstractComponentConfiguration
{
    private static bool $isNativeAPIEndpointDisabled = false;
    private static string $getNativeAPIEndpoint = '/api/';

    public static function isNativeAPIEndpointDisabled(): bool
    {
        // Define properties
        $envVariable = Environment::DISABLE_NATIVE_API_ENDPOINT;
        $selfProperty = &self::$isNativeAPIEndpointDisabled;
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

    public static function getNativeAPIEndpoint(): string
    {
        // Define properties
        $envVariable = Environment::NATIVE_API_ENDPOINT;
        $selfProperty = &self::$getNativeAPIEndpoint;
        $defaultValue = '/api/';
        $callback = [EndpointUtils::class, 'slashURI'];

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
