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
            'graphiql' => [
                'graphql/mobile-app/?view=graphiql',
            ],
            'voyager' => [
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
            'graphiql' => [
                'graphql/customers/penguin-books/?view=graphiql',
            ],
            'voyager' => [
                'graphql/customers/penguin-books/?view=schema',
            ],
        ];
    }
}
