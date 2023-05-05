<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\State;

use GatoGraphQL\GatoGraphQL\Services\EndpointExecuters\GraphQLQueryResolutionEndpointExecuterInterface;
use GatoGraphQL\GatoGraphQL\Services\EndpointExecuters\PersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter;

class PersistedQueryEndpointGraphQLQueryResolutionEndpointExecuterAppStateProvider extends AbstractGraphQLQueryResolutionEndpointExecuterAppStateProvider
{
    private ?PersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter $persistedQueryEndpointGraphQLQueryResolutionEndpointExecuter = null;

    final public function setPersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter(PersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter $persistedQueryEndpointGraphQLQueryResolutionEndpointExecuter): void
    {
        $this->persistedQueryEndpointGraphQLQueryResolutionEndpointExecuter = $persistedQueryEndpointGraphQLQueryResolutionEndpointExecuter;
    }
    final protected function getPersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter(): PersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter
    {
        /** @var PersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter */
        return $this->persistedQueryEndpointGraphQLQueryResolutionEndpointExecuter ??= $this->instanceManager->getInstance(PersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter::class);
    }

    protected function getGraphQLQueryResolutionEndpointExecuter(): GraphQLQueryResolutionEndpointExecuterInterface
    {
        return $this->getPersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter();
    }
}
