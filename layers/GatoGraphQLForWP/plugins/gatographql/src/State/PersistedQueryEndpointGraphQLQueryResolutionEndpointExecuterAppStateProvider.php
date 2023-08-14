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
        if ($this->persistedQueryEndpointGraphQLQueryResolutionEndpointExecuter === null) {
            /** @var PersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter */
            $persistedQueryEndpointGraphQLQueryResolutionEndpointExecuter = $this->instanceManager->getInstance(PersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter::class);
            $this->persistedQueryEndpointGraphQLQueryResolutionEndpointExecuter = $persistedQueryEndpointGraphQLQueryResolutionEndpointExecuter;
        }
        return $this->persistedQueryEndpointGraphQLQueryResolutionEndpointExecuter;
    }

    protected function getGraphQLQueryResolutionEndpointExecuter(): GraphQLQueryResolutionEndpointExecuterInterface
    {
        return $this->getPersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter();
    }
}
