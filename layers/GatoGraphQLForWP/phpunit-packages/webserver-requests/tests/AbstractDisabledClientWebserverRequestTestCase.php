<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Test that disabling clients (GraphiQL/Voyager) works well
 */
abstract class AbstractDisabledClientWebserverRequestTestCase extends AbstractWebserverRequestTestCase
{
    use ClientWebserverRequestTestCaseTrait;

    #[DataProvider('provideDisabledClientEntries')]
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
