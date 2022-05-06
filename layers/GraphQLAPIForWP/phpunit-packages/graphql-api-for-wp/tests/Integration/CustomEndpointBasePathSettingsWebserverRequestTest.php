<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\AbstractRequestURLPathSettingsWebserverRequestTest;

/**
 * Test that updating the base path for the custom endpoint works well
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

    /**
     * For some reason, WordPress does not produce a 404 when accessing
     * the previous CPT URL, so test on the content-type then:
     *
     * - JSON: Endpoint is accessible
     * - HTML: Some WordPress page
     */
    protected function doTestPathsUpdated(
        string $newPath,
        string $previousPath,
    ): void {
        $this->doTestEnabledOrDisabledPath($newPath, 200, 'application/json', true);
        $this->doTestEnabledOrDisabledPath($previousPath, 200, 'text/html', false);
    }
}
