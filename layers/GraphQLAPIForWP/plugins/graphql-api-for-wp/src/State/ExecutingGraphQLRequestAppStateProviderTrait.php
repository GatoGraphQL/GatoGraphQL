<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\State;

trait ExecutingGraphQLRequestAppStateProviderTrait
{
    /**
     * Due to `$appLoader->setInitialAppState($graphQLRequestAppState);`
     * in AbstractMainPlugin, ?output=json is being set always.
     *
     * As ->doingJSON can't then be used anymore to decide to print the
     * JSON response or not, we add the artificial state
     * "executing-graphql", to signify "this is indeed a GraphQL request",
     * so print the JSON.
     *
     * @see layers/GraphQLAPIForWP/plugins/graphql-api-for-wp/src/PluginSkeleton/AbstractMainPlugin.php
     * @see layers/Engine/packages/engine-wp/src/Hooks/TemplateHookSet.php
     *
     * @param array<string,mixed> $state
     */
    protected function addExecutingGraphQLState(array &$state): void
    {
        $state['executing-graphql'] = true;
    }
}
