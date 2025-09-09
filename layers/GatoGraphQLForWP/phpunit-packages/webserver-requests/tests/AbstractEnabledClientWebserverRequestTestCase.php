<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Test that enabling/disabling clients (GraphiQL/Voyager)
 * in Custom Endpoints works well
 */
abstract class AbstractEnabledClientWebserverRequestTestCase extends AbstractWebserverRequestTestCase
{
    use ClientWebserverRequestTestCaseTrait;

    #[DataProvider('provideEnabledClientEntries')]
    public function testEnabledClients(
        string $clientEndpoint,
    ): void {
        $this->doTestEnabledOrDisabledClients($clientEndpoint, 200, true);
    }

    /**
     * @return array<string,string[]>
     */
    abstract public static function provideEnabledClientEntries(): array;
}
