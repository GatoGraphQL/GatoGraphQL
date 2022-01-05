<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLEndpointForWP;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\APIEndpoints\EndpointUtils;
use PoP\BasicService\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private bool $isGraphQLAPIEndpointDisabled = false;
    private string $getGraphQLAPIEndpoint = '/api/graphql/';

    public function isGraphQLAPIEndpointDisabled(): bool
    {
        // Define properties
        $envVariable = Environment::DISABLE_GRAPHQL_API_ENDPOINT;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    public function getGraphQLAPIEndpoint(): string
    {
        // Define properties
        $envVariable = Environment::GRAPHQL_API_ENDPOINT;
        $defaultValue = '/api/graphql/';
        $callback = [EndpointUtils::class, 'slashURI'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }
}
