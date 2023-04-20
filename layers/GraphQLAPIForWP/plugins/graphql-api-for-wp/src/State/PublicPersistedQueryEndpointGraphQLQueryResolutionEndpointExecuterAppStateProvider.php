<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\State;

use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\GraphQLQueryResolutionEndpointExecuterInterface;
use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\PublicPersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter;

class PublicPersistedQueryEndpointGraphQLQueryResolutionEndpointExecuterAppStateProvider extends AbstractGraphQLQueryResolutionEndpointExecuterAppStateProvider
{
    private ?PublicPersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter $publicPersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter = null;

    final public function setPublicPersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter(PublicPersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter $publicPersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter): void
    {
        $this->publicPersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter = $publicPersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter;
    }
    final protected function getPublicPersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter(): PublicPersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter
    {
        /** @var PublicPersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter */
        return $this->publicPersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter ??= $this->instanceManager->getInstance(PublicPersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter::class);
    }

    protected function getGraphQLQueryResolutionEndpointExecuter(): GraphQLQueryResolutionEndpointExecuterInterface
    {
        return $this->getPublicPersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter();
    }
}
