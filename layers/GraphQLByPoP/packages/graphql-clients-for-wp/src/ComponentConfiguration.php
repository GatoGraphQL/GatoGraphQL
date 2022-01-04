<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLClientsForWP;

use PoP\APIEndpoints\EndpointUtils;
use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;

class ComponentConfiguration extends \PoP\BasicService\Component\AbstractComponentConfiguration
{
    private static string $getGraphQLClientsComponentURL = '';

    private static bool $isGraphiQLClientEndpointDisabled = false;
    private static string $graphiQLClientEndpoint = '/graphiql/';
    private static bool $useGraphiQLExplorer = true;
    private static bool $isGoyagerClientEndpointDisabled = false;
    private static string $voyagerClientEndpoint = '/schema/';


    /**
     * URL under which the clients are loaded.
     * Needed to convert relative paths to absolute URLs
     */
    public static function getGraphQLClientsComponentURL(): string
    {
        // Define properties
        $envVariable = Environment::GRAPHQL_CLIENTS_COMPONENT_URL;
        $selfProperty = &self::$getGraphQLClientsComponentURL;
        $defaultValue = '';

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue
        );
        return $selfProperty;
    }

    /**
     * Is the GraphiQL client disabled?
     */
    public static function isGraphiQLClientEndpointDisabled(): bool
    {
        // Define properties
        $envVariable = Environment::DISABLE_GRAPHIQL_CLIENT_ENDPOINT;
        $selfProperty = &self::$isGraphiQLClientEndpointDisabled;
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

    /**
     * Use the GraphiQL explorer?
     */
    public static function useGraphiQLExplorer(): bool
    {
        // Define properties
        $envVariable = Environment::USE_GRAPHIQL_EXPLORER;
        $selfProperty = &self::$useGraphiQLExplorer;
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

    /**
     * GraphiQL client endpoint, to be executed against the GraphQL single endpoint
     */
    public static function getGraphiQLClientEndpoint(): string
    {
        // Define properties
        $envVariable = Environment::GRAPHIQL_CLIENT_ENDPOINT;
        $selfProperty = &self::$graphiQLClientEndpoint;
        $defaultValue = '/graphiql/';
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

    /**
     * Is the Voyager client disabled?
     */
    public static function isVoyagerClientEndpointDisabled(): bool
    {
        // Define properties
        $envVariable = Environment::DISABLE_VOYAGER_CLIENT_ENDPOINT;
        $selfProperty = &self::$isGoyagerClientEndpointDisabled;
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

    /**
     * Voyager client endpoint, to be executed against the GraphQL single endpoint
     */
    public static function getVoyagerClientEndpoint(): string
    {
        // Define properties
        $envVariable = Environment::VOYAGER_CLIENT_ENDPOINT;
        $selfProperty = &self::$voyagerClientEndpoint;
        $defaultValue = '/schema/';
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
