<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use GraphQLByPoP\GraphQLClientsForWP\Constants\CustomHeaders;

/**
 * Test that enabling/disabling clients (GraphiQL/Voyager)
 * in Custom Endpoints works well
 */
trait ClientWebserverRequestTestCaseTrait
{
    /**
     * @return int|string
     */
    abstract public function dataName();

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
        $response = $client->get($clientEndpointURL, $options);

        // Disabled clients: they may assert it produced a 404
        $this->assertEquals($expectedStatusCode, $response->getStatusCode());

        // Enable clients: must return a custom header, check it is there
        $hasCustomHeader = $response->hasHeader(CustomHeaders::CLIENT_ENDPOINT);
        $this->assertTrue($enabled ? $hasCustomHeader : !$hasCustomHeader);
    }
}
