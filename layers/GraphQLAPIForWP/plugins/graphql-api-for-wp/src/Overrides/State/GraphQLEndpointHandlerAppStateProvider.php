<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Overrides\State;

use GraphQLAPI\GraphQLAPI\State\ExecutingGraphQLRequestAppStateProviderTrait;
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
