<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLEndpointForWP;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoPAPI\APIEndpoints\EndpointUtils;
use PoP\Root\Module\EnvironmentValueHelpers;

class ModuleConfiguration extends AbstractModuleConfiguration
{
    public function isGraphQLAPIEndpointDisabled(): bool
    {
        $envVariable = Environment::DISABLE_GRAPHQL_API_ENDPOINT;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function getGraphQLAPIEndpoint(): string
    {
        $envVariable = Environment::GRAPHQL_API_ENDPOINT;
        $defaultValue = '/api/graphql/';
        $callback = [EndpointUtils::class, 'slashURI'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
