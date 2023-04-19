<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\State;

use PoP\Root\State\AbstractAppStateProvider;

/**
 * State "executing-graphql" signifies "this is a request for a GraphQL endpoint"
 */
class ExecutingGraphQLRequestAppStateProvider extends AbstractAppStateProvider
{
    /**
     * Because we don't know in which order are the
     * AppStateProviders executed, if the one setting
     * the value in `true` was already executed, then
     * do not override that value.
     *
     * @param array<string,mixed> $state
     */
    public function initialize(array &$state): void
    {
        if (isset($state['executing-graphql'])) {
            return;
        }
        $state['executing-graphql'] = false;
    }
}
