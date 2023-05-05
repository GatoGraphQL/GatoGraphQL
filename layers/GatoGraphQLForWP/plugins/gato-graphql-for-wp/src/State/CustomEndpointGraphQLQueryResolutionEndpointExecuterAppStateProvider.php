<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\State;

use GatoGraphQL\GatoGraphQL\Services\EndpointExecuters\GraphQLQueryResolutionEndpointExecuterInterface;
use GatoGraphQL\GatoGraphQL\Services\EndpointExecuters\CustomEndpointGraphQLQueryResolutionEndpointExecuter;

class CustomEndpointGraphQLQueryResolutionEndpointExecuterAppStateProvider extends AbstractGraphQLQueryResolutionEndpointExecuterAppStateProvider
{
    private ?CustomEndpointGraphQLQueryResolutionEndpointExecuter $customEndpointGraphQLQueryResolutionEndpointExecuter = null;

    final public function setCustomEndpointGraphQLQueryResolutionEndpointExecuter(CustomEndpointGraphQLQueryResolutionEndpointExecuter $customEndpointGraphQLQueryResolutionEndpointExecuter): void
    {
        $this->customEndpointGraphQLQueryResolutionEndpointExecuter = $customEndpointGraphQLQueryResolutionEndpointExecuter;
    }
    final protected function getCustomEndpointGraphQLQueryResolutionEndpointExecuter(): CustomEndpointGraphQLQueryResolutionEndpointExecuter
    {
        /** @var CustomEndpointGraphQLQueryResolutionEndpointExecuter */
        return $this->customEndpointGraphQLQueryResolutionEndpointExecuter ??= $this->instanceManager->getInstance(CustomEndpointGraphQLQueryResolutionEndpointExecuter::class);
    }

    protected function getGraphQLQueryResolutionEndpointExecuter(): GraphQLQueryResolutionEndpointExecuterInterface
    {
        return $this->getCustomEndpointGraphQLQueryResolutionEndpointExecuter();
    }
}
