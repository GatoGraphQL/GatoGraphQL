<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

/**
 * Test that disabling clients (GraphiQL/Voyager) works well
 */
abstract class AbstractDisabledClientWebserverRequestTestCase extends AbstractWebserverRequestTestCase
{
    use ClientWebserverRequestTestCaseTrait;

    /**
     * @dataProvider provideDisabledClientEntries
     */
    public function testDisabledClients(
        string $clientEndpoint,
        int $expectedStatusCode,
    ): void {
        $this->testEnabledOrDisabledClients($clientEndpoint, $expectedStatusCode, false);
    }

    /**
     * @return array<string,mixed[]>
     */
    abstract protected function provideDisabledClientEntries(): array;
}
