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
        $client = static::getClient();
        $clientEndpointURL = static::getWebserverHomeURL() . '/' . $clientEndpoint;
        $response = $client->get(
            $clientEndpointURL,
        );
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($response->hasHeader(CustomHeaders::CLIENT_ENDPOINT));
    }

    /**
     * @return array<string,string[]>
     */
    abstract protected function provideEnabledClientEntries(): array;

    /**
     * @dataProvider provideDisabledClientEntries
     */
    public function testDisabledClients(
        string $clientEndpoint,
    ): void {
        $client = static::getClient();
        $clientEndpointURL = static::getWebserverHomeURL() . '/' . $clientEndpoint;
        $response = $client->get(
            $clientEndpointURL,
        );
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertFalse($response->hasHeader('X-Client-Endpoint'));
    }

    /**
     * @return array<string,string[]>
     */
    abstract protected function provideDisabledClientEntries(): array;
}
