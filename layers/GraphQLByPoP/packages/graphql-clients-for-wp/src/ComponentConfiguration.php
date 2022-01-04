<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLClientsForWP;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\APIEndpoints\EndpointUtils;
use PoP\BasicService\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private string $getGraphQLClientsComponentURL = '';

    private bool $isGraphiQLClientEndpointDisabled = false;
    private string $graphiQLClientEndpoint = '/graphiql/';
    private bool $useGraphiQLExplorer = true;
    private bool $isGoyagerClientEndpointDisabled = false;
    private string $voyagerClientEndpoint = '/schema/';


    /**
     * URL under which the clients are loaded.
     * Needed to convert relative paths to absolute URLs
     */
    public function getGraphQLClientsComponentURL(): string
    {
        // Define properties
        $envVariable = Environment::GRAPHQL_CLIENTS_COMPONENT_URL;
        $selfProperty = &$this->getGraphQLClientsComponentURL;
        $defaultValue = '';

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue
        );
        return $selfProperty;
    }

    /**
     * Is the GraphiQL client disabled?
     */
    public function isGraphiQLClientEndpointDisabled(): bool
    {
        // Define properties
        $envVariable = Environment::DISABLE_GRAPHIQL_CLIENT_ENDPOINT;
        $selfProperty = &$this->isGraphiQLClientEndpointDisabled;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
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
    public function useGraphiQLExplorer(): bool
    {
        // Define properties
        $envVariable = Environment::USE_GRAPHIQL_EXPLORER;
        $selfProperty = &$this->useGraphiQLExplorer;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
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
    public function getGraphiQLClientEndpoint(): string
    {
        // Define properties
        $envVariable = Environment::GRAPHIQL_CLIENT_ENDPOINT;
        $selfProperty = &$this->graphiQLClientEndpoint;
        $defaultValue = '/graphiql/';
        $callback = [EndpointUtils::class, 'slashURI'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
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
    public function isVoyagerClientEndpointDisabled(): bool
    {
        // Define properties
        $envVariable = Environment::DISABLE_VOYAGER_CLIENT_ENDPOINT;
        $selfProperty = &$this->isGoyagerClientEndpointDisabled;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
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
    public function getVoyagerClientEndpoint(): string
    {
        // Define properties
        $envVariable = Environment::VOYAGER_CLIENT_ENDPOINT;
        $selfProperty = &$this->voyagerClientEndpoint;
        $defaultValue = '/schema/';
        $callback = [EndpointUtils::class, 'slashURI'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }
}
