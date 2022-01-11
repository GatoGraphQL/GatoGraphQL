<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\State;

use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\GraphQLQueryResolutionEndpointExecuterInterface;
use PoP\Root\State\AbstractAppStateProvider;

abstract class AbstractEndpointExecuterAppStateProvider extends AbstractAppStateProvider
{
    abstract protected function getGraphQLQueryResolutionEndpointExecuter(): GraphQLQueryResolutionEndpointExecuterInterface;

    public function isServiceEnabled(): bool
    {
        return $this->getGraphQLQueryResolutionEndpointExecuter()->isServiceEnabled();
    }
}
