<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\AbstractRequestURLPathSettingsWebserverRequestTestCase;

/**
 * Test that updating the base path for the persisted queries works well
 */
abstract class AbstractPersistedQueryBasePathSettingsWebserverRequestTestCase extends AbstractRequestURLPathSettingsWebserverRequestTestCase
{
    /**
     * @return array<string,string[]> Array of 1 element: [ ${newPath} ]
     */
    protected function providePathEntries(): array
    {
        return [
            'persisted-query-base-path' => [
                $this->getNonExistingPathEntry(),
            ],
        ];
    }

    abstract protected function getNonExistingPathEntry(): string;

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
