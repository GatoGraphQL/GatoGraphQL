<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\State;

use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\GraphQLQueryResolutionEndpointExecuterInterface;
use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\CustomEndpointGraphQLQueryResolutionEndpointExecuter;

class CustomEndpointGraphQLQueryResolutionEndpointExecuterAppStateProvider extends AbstractGraphQLQueryResolutionEndpointExecuterAppStateProvider
{
    private ?CustomEndpointGraphQLQueryResolutionEndpointExecuter $customEndpointGraphQLQueryResolutionEndpointExecuter = null;

    final public function setCustomEndpointGraphQLQueryResolutionEndpointExecuter(CustomEndpointGraphQLQueryResolutionEndpointExecuter $customEndpointGraphQLQueryResolutionEndpointExecuter): void
    {
        $this->customEndpointGraphQLQueryResolutionEndpointExecuter = $customEndpointGraphQLQueryResolutionEndpointExecuter;
    }
    final protected function getCustomEndpointGraphQLQueryResolutionEndpointExecuter(): CustomEndpointGraphQLQueryResolutionEndpointExecuter
    {
        return $this->customEndpointGraphQLQueryResolutionEndpointExecuter ??= $this->instanceManager->getInstance(CustomEndpointGraphQLQueryResolutionEndpointExecuter::class);
    }

    protected function getGraphQLQueryResolutionEndpointExecuter(): GraphQLQueryResolutionEndpointExecuterInterface
    {
        return $this->getCustomEndpointGraphQLQueryResolutionEndpointExecuter();
    }
}
