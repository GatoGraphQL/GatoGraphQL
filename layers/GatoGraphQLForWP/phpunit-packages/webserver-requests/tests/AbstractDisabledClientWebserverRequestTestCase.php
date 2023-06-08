<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

/**
 * Test that disabling clients (GraphiQL/Voyager) works well
 */
abstract class AbstractDisabledClientWebserverRequestTestCase extends AbstractWebserverRequestTestCase
{
    use ClientWebserverRequestTestCaseTrait;

    #[\PHPUnit\Framework\Attributes\DataProvider('provideDisabledClientEntries')]
    public function testDisabledClients(
        string $clientEndpoint,
        int $expectedStatusCode,
    ): void {
        $this->doTestEnabledOrDisabledClients($clientEndpoint, $expectedStatusCode, false);
    }

    /**
     * @return array<string,mixed[]>
     */
    abstract public static function provideDisabledClientEntries(): array;
}
