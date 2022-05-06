<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\AbstractRequestURLPathSettingsWebserverRequestTest;

/**
 * Test that updating the path for a client (GraphiQL/Voyager) works well
 */
class CustomEndpointBasePathSettingsWebserverRequestTest extends AbstractRequestURLPathSettingsWebserverRequestTest
{
    /**
     * @return array<string,string[]> Array of 1 element: [ ${newClientPath} ]
     */
    protected function providePathEntries(): array
    {
        return [
            'custom-endpoint-base-path' => [
                'graaaaaaaphql',
            ],
            'slashed-custom-endpoint-base-path' => [
                '/graaaaaaappppppphql/',
            ],
        ];
    }

    protected function getModuleID(string $dataName): string
    {
        return 'graphqlapi_graphqlapi_custom-endpoints';
    }

    protected function getNewClientPath(string $dataItem): string
    {
        return $dataItem . '/power-users/';
    }
}
