<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLEndpointForWP;

use PoP\APIEndpoints\EndpointUtils;
use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;

class ComponentConfiguration extends \PoP\BasicService\Component\AbstractComponentConfiguration
{
    private static bool $isGraphQLAPIEndpointDisabled = false;
    private static string $getGraphQLAPIEndpoint = '/api/graphql/';

    public static function isGraphQLAPIEndpointDisabled(): bool
    {
        // Define properties
        $envVariable = Environment::DISABLE_GRAPHQL_API_ENDPOINT;
        $selfProperty = &self::$isGraphQLAPIEndpointDisabled;
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

    public static function getGraphQLAPIEndpoint(): string
    {
        // Define properties
        $envVariable = Environment::GRAPHQL_API_ENDPOINT;
        $selfProperty = &self::$getGraphQLAPIEndpoint;
        $defaultValue = '/api/graphql/';
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
