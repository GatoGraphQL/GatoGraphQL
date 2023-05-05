<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Overrides\State;

use GatoGraphQL\GatoGraphQL\State\ExecutingGraphQLRequestAppStateProviderTrait;
use GraphQLByPoP\GraphQLEndpointForWP\State\GraphQLEndpointHandlerAppStateProvider as UpstreamGraphQLEndpointHandlerAppStateProvider;

class GraphQLEndpointHandlerAppStateProvider extends UpstreamGraphQLEndpointHandlerAppStateProvider
{
    use ExecutingGraphQLRequestAppStateProviderTrait;

    /**
     * @param array<string,mixed> $state
     */
    public function initialize(array &$state): void
    {
        parent::initialize($state);

        /**
         * Artificial state, to signify that this is indeed
         * a GraphQL request.
         */
        $this->addExecutingGraphQLState($state);
    }
}
