<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\State;

use GatoGraphQL\GatoGraphQL\Services\EndpointExecuters\GraphQLEndpointExecuterInterface;
use GatoGraphQL\GatoGraphQL\Services\EndpointExecuters\GraphQLQueryResolutionEndpointExecuterInterface;

abstract class AbstractGraphQLQueryResolutionEndpointExecuterAppStateProvider extends AbstractGraphQLEndpointExecuterAppStateProvider
{
    protected function getGraphQLEndpointExecuter(): GraphQLEndpointExecuterInterface
    {
        return $this->getGraphQLQueryResolutionEndpointExecuter();
    }

    abstract protected function getGraphQLQueryResolutionEndpointExecuter(): GraphQLQueryResolutionEndpointExecuterInterface;
}
