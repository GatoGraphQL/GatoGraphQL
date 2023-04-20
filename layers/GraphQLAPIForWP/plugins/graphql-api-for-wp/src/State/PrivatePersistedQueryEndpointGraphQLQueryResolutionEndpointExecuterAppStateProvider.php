<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\State;

use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\GraphQLQueryResolutionEndpointExecuterInterface;
use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\PrivatePersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter;

class PrivatePersistedQueryEndpointGraphQLQueryResolutionEndpointExecuterAppStateProvider extends AbstractGraphQLQueryResolutionEndpointExecuterAppStateProvider
{
    private ?PrivatePersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter $privatePersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter = null;

    final public function setPrivatePersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter(PrivatePersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter $privatePersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter): void
    {
        $this->privatePersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter = $privatePersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter;
    }
    final protected function getPrivatePersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter(): PrivatePersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter
    {
        /** @var PrivatePersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter */
        return $this->privatePersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter ??= $this->instanceManager->getInstance(PrivatePersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter::class);
    }

    protected function getGraphQLQueryResolutionEndpointExecuter(): GraphQLQueryResolutionEndpointExecuterInterface
    {
        return $this->getPrivatePersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter();
    }
}
