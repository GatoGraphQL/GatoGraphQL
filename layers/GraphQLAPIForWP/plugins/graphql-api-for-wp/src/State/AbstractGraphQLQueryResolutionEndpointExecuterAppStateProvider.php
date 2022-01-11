<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\State;

use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\EndpointExecuterInterface;
use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\GraphQLQueryResolutionEndpointExecuterInterface;

abstract class AbstractGraphQLQueryResolutionEndpointExecuterAppStateProvider extends AbstractEndpointExecuterAppStateProvider
{
    protected function getEndpointExecuter(): EndpointExecuterInterface
    {
        return $this->getGraphQLQueryResolutionEndpointExecuter();
    }

    abstract protected function getGraphQLQueryResolutionEndpointExecuter(): GraphQLQueryResolutionEndpointExecuterInterface;
}
