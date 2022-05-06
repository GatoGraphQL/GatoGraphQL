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
     * @return array<string,string[]> Array of 1 element: [ ${newPath} ]
     */
    protected function providePathEntries(): array
    {
        return [
            'custom-endpoint-base-path' => [
                'graaaaaaaphql',
            ],
        ];
    }

    protected function getModuleID(string $dataName): string
    {
        return 'graphqlapi_graphqlapi_custom-endpoints';
    }

    protected function getNewPath(string $dataItem): string
    {
        return $this->getCustomEndpointURL($dataItem);
    }

    protected function getPreviousPath(string $previousPath): string
    {
        return $this->getCustomEndpointURL($previousPath);
    }

    protected function getCustomEndpointURL(string $basePath): string
    {
        return $basePath . '/power-users/';
    }
}
