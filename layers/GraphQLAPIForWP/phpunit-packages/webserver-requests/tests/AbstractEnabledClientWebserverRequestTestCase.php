<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

/**
 * Test that enabling/disabling clients (GraphiQL/Voyager)
 * in Custom Endpoints works well
 */
abstract class AbstractEnabledClientWebserverRequestTestCaseCase extends AbstractWebserverRequestTestCaseCase
{
    use ClientWebserverRequestTestCaseTrait;

    /**
     * @dataProvider provideEnabledClientEntries
     */
    public function testEnabledClients(
        string $clientEndpoint,
    ): void {
        $this->doTestEnabledOrDisabledClients($clientEndpoint, 200, true);
    }

    /**
     * @return array<string,string[]>
     */
    abstract protected function provideEnabledClientEntries(): array;
}
