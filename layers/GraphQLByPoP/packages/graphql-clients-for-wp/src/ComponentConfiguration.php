<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLClientsForWP;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\APIEndpoints\EndpointUtils;
use PoP\BasicService\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    /**
     * URL under which the clients are loaded.
     * Needed to convert relative paths to absolute URLs
     */
    public function getGraphQLClientsComponentURL(): string
    {
        // Define properties
        $envVariable = Environment::GRAPHQL_CLIENTS_COMPONENT_URL;
        $defaultValue = '';

        // Initialize property from the environment/hook
        $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
        return $this->configuration[$envVariable];
    }

    /**
     * Is the GraphiQL client disabled?
     */
    public function isGraphiQLClientEndpointDisabled(): bool
    {
        // Define properties
        $envVariable = Environment::DISABLE_GRAPHIQL_CLIENT_ENDPOINT;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    /**
     * Use the GraphiQL explorer?
     */
    public function useGraphiQLExplorer(): bool
    {
        // Define properties
        $envVariable = Environment::USE_GRAPHIQL_EXPLORER;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    /**
     * GraphiQL client endpoint, to be executed against the GraphQL single endpoint
     */
    public function getGraphiQLClientEndpoint(): string
    {
        // Define properties
        $envVariable = Environment::GRAPHIQL_CLIENT_ENDPOINT;
        $defaultValue = '/graphiql/';
        $callback = [EndpointUtils::class, 'slashURI'];

        // Initialize property from the environment/hook
        $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    /**
     * Is the Voyager client disabled?
     */
    public function isVoyagerClientEndpointDisabled(): bool
    {
        // Define properties
        $envVariable = Environment::DISABLE_VOYAGER_CLIENT_ENDPOINT;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    /**
     * Voyager client endpoint, to be executed against the GraphQL single endpoint
     */
    public function getVoyagerClientEndpoint(): string
    {
        // Define properties
        $envVariable = Environment::VOYAGER_CLIENT_ENDPOINT;
        $defaultValue = '/schema/';
        $callback = [EndpointUtils::class, 'slashURI'];

        // Initialize property from the environment/hook
        $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }
}
