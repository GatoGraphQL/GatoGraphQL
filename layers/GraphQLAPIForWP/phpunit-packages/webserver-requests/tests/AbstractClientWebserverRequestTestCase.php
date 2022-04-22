<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use Exception;
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
        $this->testEnabledOrDisabledClients($clientEndpoint, 200, true);
    }

    /**
     * @return array<string,string[]>
     */
    abstract protected function provideEnabledClientEntries(): array;

    protected function testEnabledOrDisabledClients(
        string $clientEndpoint,
        int $expectedStatusCode,
        bool $enabled
    ): void {
        $client = static::getClient();
        $clientEndpointURL = static::getWebserverHomeURL() . '/' . $clientEndpoint;
        $options = [
            'verify' => false,
            // Don't throw exception with 404
            'http_errors' => false,
        ];

        $dataName = $this->dataName();
        // Set-up: Allow to execute a REST endpoint against the webserver
        $this->beforeFixtureClientRequest($dataName, $clientEndpoint, $enabled);
        $exception = null;
        try {
            $response = $client->get($clientEndpointURL, $options);
        } catch (Exception $e) {
            $exception = $e;
        } finally {
            // Clean-up: Allow to execute a REST endpoint against the webserver
            $this->afterFixtureClientRequest($dataName, $clientEndpoint, $enabled);
        }

        if ($exception !== null) {
            throw $exception;
        }

        // Disabled clients: they may assert it produced a 404
        $this->assertEquals($expectedStatusCode, $response->getStatusCode());

        // Enable clients: must return a custom header, check it is there
        $hasCustomHeader = $response->hasHeader(CustomHeaders::CLIENT_ENDPOINT);
        $this->assertTrue($enabled ? $hasCustomHeader : !$hasCustomHeader);
    }

    /**
     * Allow to execute a REST endpoint against the webserver
     * before running the test
     */
    protected function beforeFixtureClientRequest(
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
    protected function afterFixtureClientRequest(
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
        int $expectedStatusCode,
    ): void {
        $this->testEnabledOrDisabledClients($clientEndpoint, $expectedStatusCode, false);
    }

    /**
     * @return array<string,mixed[]>
     */
    abstract protected function provideDisabledClientEntries(): array;
}
