<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\AbstractRequestURLPathSettingsWebserverRequestTest;

/**
 * Test that updating the base path for the persisted queries works well
 */
abstract class AbstractPersistedQueryBasePathSettingsWebserverRequestTest extends AbstractRequestURLPathSettingsWebserverRequestTest
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

    protected function getNewPath(string $dataItem): string
    {
        return $this->getPersistedQueryURL($dataItem);
    }

    protected function getPreviousPath(string $previousPath): string
    {
        return $this->getPersistedQueryURL($previousPath);
    }

    abstract protected function getPersistedQueryURL(string $basePath): string;

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
