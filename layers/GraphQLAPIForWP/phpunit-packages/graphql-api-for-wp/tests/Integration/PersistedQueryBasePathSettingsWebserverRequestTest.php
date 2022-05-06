<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\AbstractRequestURLPathSettingsWebserverRequestTest;

/**
 * Test that updating the base path for the persisted queries works well
 */
class PersistedQueryBasePathSettingsWebserverRequestTest extends AbstractRequestURLPathSettingsWebserverRequestTest
{
    /**
     * @return array<string,string[]> Array of 1 element: [ ${newPath} ]
     */
    protected function providePathEntries(): array
    {
        return [
            'persisted-query-base-path' => [
                'graaaaaaaphql-queeeeery',
            ],
        ];
    }

    protected function getModuleID(string $dataName): string
    {
        return 'graphqlapi_graphqlapi_persisted-queries';
    }

    protected function getNewPath(string $dataItem): string
    {
        return $this->getPersistedQueryURL($dataItem);
    }

    protected function getPreviousPath(string $previousPath): string
    {
        return $this->getPersistedQueryURL($previousPath);
    }

    protected function getPersistedQueryURL(string $basePath): string
    {
        return $basePath . '/latest-posts-for-mobile-app/';
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
        $this->testEnabledOrDisabledPath($newPath, 200, 'application/json', true);

        /**
         * Disabled because WordPress still loads the previous URL,
         * doing a redirect to the new URL
         */
        // $this->testEnabledOrDisabledPath($previousPath, 200, 'text/html', false);
    }
}
