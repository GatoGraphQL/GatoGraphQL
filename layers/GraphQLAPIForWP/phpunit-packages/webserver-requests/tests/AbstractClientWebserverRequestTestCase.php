<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use GraphQLByPoP\GraphQLClientsForWP\Constants\CustomHeaders;

/**
 * Test that enabling/disabling clients (GraphiQL/Voyager)
 * in Custom Endpoints works well
 */
abstract class AbstractClientWebserverRequestTestCase extends AbstractWebserverRequestTestCase
{
    /**
     * @dataProvider provideEnabledClientEntries
     */
    public function testEnabledClients(
        string $clientEndpoint,
    ): void {
        $this->testEnabledOrDisabledClients($clientEndpoint, true);
    }

    /**
     * @return array<string,string[]>
     */
    abstract protected function provideEnabledClientEntries(): array;

    protected function testEnabledOrDisabledClients(
        string $clientEndpoint,
        bool $enabled
    ): void {
        $dataName = $this->dataName();
        /**
         * Allow to execute a REST endpoint against the webserver
         * before running the test
         */
        $this->beforeRunningTest($dataName, $clientEndpoint, $enabled);

        $client = static::getClient();
        $clientEndpointURL = static::getWebserverHomeURL() . '/' . $clientEndpoint;
        $options = [
            'verify' => false,
        ];
        $response = $client->get($clientEndpointURL, $options);

        /**
         * Allow to execute a REST endpoint against the webserver
         * after running the test
         */
        $this->afterRunningTest($dataName, $clientEndpoint, $enabled);

        $this->assertEquals(200, $response->getStatusCode());
        $hasCustomHeader = $response->hasHeader(CustomHeaders::CLIENT_ENDPOINT);
        $this->assertTrue($enabled ? $hasCustomHeader : !$hasCustomHeader);
    }

    /**
     * Allow to execute a REST endpoint against the webserver
     * before running the test
     */
    protected function beforeRunningTest(
        string $dataName,
        string $clientEndpoint,
        bool $enabled,
    ): void {
        // Override if needed
    }

    /**
     * Allow to execute a REST endpoint against the webserver
     * after running the test
     */
    protected function afterRunningTest(
        string $dataName,
        string $clientEndpoint,
        bool $enabled,
    ): void {
        // Override if needed
    }

    /**
     * @dataProvider provideDisabledClientEntries
     */
    public function testDisabledClients(
        string $clientEndpoint,
    ): void {
        $this->testEnabledOrDisabledClients($clientEndpoint, false);
    }

    /**
     * @return array<string,string[]>
     */
    abstract protected function provideDisabledClientEntries(): array;
}
