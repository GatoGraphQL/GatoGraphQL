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

    protected function doTestPathsUpdated(
        string $newPath,
        string $previousPath,
    ): void {
        $this->doTestEnabledOrDisabledPath($newPath, 200, 'application/json', true);

        /**
         * Disabled because WordPress still loads the previous URL,
         * doing a redirect to the new URL
         */
        // $this->doTestEnabledOrDisabledPath($previousPath, 200, 'text/html', false);
    }
}
