<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

/**
 * Test that enabling/disabling clients (GraphiQL/Voyager)
 * in Custom Endpoints works well
 */
abstract class AbstractClientPathSettingsWebserverRequestTestCase extends AbstractRequestURLPathSettingsWebserverRequestTestCase
{
    use ClientWebserverRequestTestCaseTrait;

    protected function doTestPathsUpdated(
        string $newPath,
        string $previousPath,
    ): void {
        $this->doTestEnabledOrDisabledPath($newPath, 200, 'text/html', true);
        $this->doTestEnabledOrDisabledPath($previousPath, 404, null, false);
    }

    /**
     * @return array<string,string[]> Array of 1 element: [ ${newPath} ]
     */
    public static function providePathEntries(): array
    {
        return static::provideClientPathEntries();
    }

    /**
     * @return array<string,string[]> Array of 1 element: [ ${newClientPath} ]
     */
    abstract protected static function provideClientPathEntries(): array;
}
