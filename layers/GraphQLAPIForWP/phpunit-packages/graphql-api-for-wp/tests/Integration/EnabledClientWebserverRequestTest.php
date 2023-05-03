<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\AbstractEnabledClientWebserverRequestTestCaseCase;

/**
 * Test that enabling clients (GraphiQL/Voyager) works well
 */
class EnabledClientWebserverRequestTest extends AbstractEnabledClientWebserverRequestTestCaseCase
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
}
