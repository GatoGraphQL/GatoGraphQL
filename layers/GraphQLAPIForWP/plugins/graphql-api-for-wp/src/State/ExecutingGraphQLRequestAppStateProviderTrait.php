<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\State;

trait ExecutingGraphQLRequestAppStateProviderTrait
{
    /**
     * State "executing-graphql" signifies "this is a request for a GraphQL endpoint"
     *
     * @param array<string,mixed> $state
     */
    protected function addExecutingGraphQLState(array &$state): void
    {
        $state['executing-graphql'] = true;
    }
}
