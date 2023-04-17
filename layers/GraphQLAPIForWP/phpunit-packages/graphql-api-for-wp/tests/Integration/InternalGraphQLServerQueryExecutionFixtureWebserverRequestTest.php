<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\GraphQLAPITesting\Constants\Actions;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Engine\Constants\Actions as EngineActions;

/**
 * Test the InternalGraphQLServer. It works like this:
 *
 * In the JSON response there is entry "internalGraphQLServerResponse",
 * which contains the response to the same requested GraphQL query,
 * but executed via `GraphQLServer`, using the configuration
 * of the InternalGraphQLServer.
 *
 * Hence, the response under this entry will be different
 * that the original response, as the two are using different
 * configurations.
 */
class InternalGraphQLServerQueryExecutionFixtureWebserverRequestTest extends AbstractFixtureEndpointWebserverRequestTestCase
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-internal-graphql-server';
    }

    /**
     * Enable the "InternalGraphQLServer" testing via
     * the endpoint
     */
    protected function getEndpoint(): string
    {
        return GeneralUtils::addQueryArgs(
            [
                'actions' => [
                    Actions::TEST_INTERNAL_GRAPHQL_SERVER,
                    EngineActions::ENABLE_APP_STATE_FIELDS,
                ],
            ],
            'graphql/'
        );
    }
}
