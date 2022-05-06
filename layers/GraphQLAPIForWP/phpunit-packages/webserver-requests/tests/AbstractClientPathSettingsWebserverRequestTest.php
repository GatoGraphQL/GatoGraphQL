<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

/**
 * Test that enabling/disabling clients (GraphiQL/Voyager)
 * in Custom Endpoints works well
 */
abstract class AbstractClientPathSettingsWebserverRequestTest extends AbstractRequestURLPathSettingsWebserverRequestTest
{
    use ClientWebserverRequestTestCaseTrait;

    /**
     * @return array<string,string[]> Array of 1 element: [ ${newClientPath} ]
     */
    protected function providePathEntries(): array
    {
        return $this->provideClientPathEntries();
    }

    /**
     * @return array<string,string[]> Array of 1 element: [ ${newClientPath} ]
     */
    abstract protected function provideClientPathEntries(): array;
}
