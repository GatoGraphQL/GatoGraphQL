<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPITesting\Executers;

use PHPUnitForGraphQLAPI\GraphQLAPITesting\Constants\Actions;
use PoP\Root\App;
use PoP\Root\Constants\HookNames;

use function add_action;

class InternalGraphQLServerTestExecuter
{
    public function __construct()
    {
        add_action(
            HookNames::APPLICATION_READY,
            $this->maybeSetupInternalGraphQLServerTesting(...)
        );
    }

    /**
     * When resolving a GraphQL query, check if the params
     * are set in the request to test the `InternalGraphQLServer`
     */
    public function maybeSetupInternalGraphQLServerTesting(): void
    {
        if (!App::getState('executing-graphql')) {
            return;
        }

        /** @var string[] */
        $actions = App::getState('actions');
        if (!in_array(Actions::TEST_INTERNAL_GRAPHQL_SERVER, $actions)) {
            return;
        }

        $this->setupInternalGraphQLServerTesting();
    }

    /**
     * Set-up the GraphQL queries to execute to test
     * the `InternalGraphQLServer`
     */
    protected function setupInternalGraphQLServerTesting(): void
    {
        // Do something
    }
}
