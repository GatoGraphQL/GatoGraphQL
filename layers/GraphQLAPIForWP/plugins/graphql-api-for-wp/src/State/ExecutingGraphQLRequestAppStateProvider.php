<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\State;

use PoP\Root\State\AbstractAppStateProvider;

/**
 * Create an artificial state just to indicate
 * if we are indeed executing the GraphQL query
 * or not, as ->doingJSON can't be relied-upon.
 *
 * @see layers/GraphQLAPIForWP/plugins/graphql-api-for-wp/src/PluginSkeleton/AbstractMainPlugin.php
 * @see layers/Engine/packages/engine-wp/src/Hooks/TemplateHookSet.php
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
