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
        $envVariable = Environment::GRAPHQL_CLIENTS_COMPONENT_URL;
        $defaultValue = '';

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
    }

    /**
     * Is the GraphiQL client disabled?
     */
    public function isGraphiQLClientEndpointDisabled(): bool
    {
        $envVariable = Environment::DISABLE_GRAPHIQL_CLIENT_ENDPOINT;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    /**
     * Use the GraphiQL explorer?
     */
    public function useGraphiQLExplorer(): bool
    {
        $envVariable = Environment::USE_GRAPHIQL_EXPLORER;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    /**
     * GraphiQL client endpoint, to be executed against the GraphQL single endpoint
     */
    public function getGraphiQLClientEndpoint(): string
    {
        $envVariable = Environment::GRAPHIQL_CLIENT_ENDPOINT;
        $defaultValue = '/graphiql/';
        $callback = [EndpointUtils::class, 'slashURI'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    /**
     * Is the Voyager client disabled?
     */
    public function isVoyagerClientEndpointDisabled(): bool
    {
        $envVariable = Environment::DISABLE_VOYAGER_CLIENT_ENDPOINT;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    /**
     * Voyager client endpoint, to be executed against the GraphQL single endpoint
     */
    public function getVoyagerClientEndpoint(): string
    {
        $envVariable = Environment::VOYAGER_CLIENT_ENDPOINT;
        $defaultValue = '/schema/';
        $callback = [EndpointUtils::class, 'slashURI'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
