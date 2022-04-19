<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\AbstractClientWebserverRequestTestCase;

/**
 * Test that enabling/disabling clients (GraphiQL/Voyager)
 * in Custom Endpoints works well
 */
class ClientWebserverRequestTest extends AbstractClientWebserverRequestTestCase
{
    /**
     * @return array<string,string[]>
     */
    protected function provideEnabledClientEntries(): array
    {
        return [
            'single-endpoint-graphiql' => [
                'graphiql/',
            ],
            'single-endpoint-voyager' => [
                'schema/',
            ],
            'custom-endpoint-graphiql' => [
                'graphql/mobile-app/?view=graphiql',
            ],
            'custom-endpoint-voyager' => [
                'graphql/mobile-app/?view=schema',
            ],
        ];
    }

    /**
     * @return array<string,string[]>
     */
    protected function provideDisabledClientEntries(): array
    {
        return [
            'custom-endpoint-graphiql' => [
                'graphql/customers/penguin-books/?view=graphiql',
            ],
            'custom-endpoint-voyager' => [
                'graphql/customers/penguin-books/?view=schema',
            ],
        ];
    }
}
