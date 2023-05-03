<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

abstract class AbstractCacheControlWebserverRequestTestCase extends AbstractWebserverRequestTestCase
{
    /**
     * @dataProvider provideCacheControlEntries
     */
    public function testCacheControl(
        string $endpoint,
        string $expectedCacheControlValue,
    ): void {
        $client = static::getClient();
        $endpointURL = static::getWebserverHomeURL() . '/' . $endpoint;
        $options = static::getRequestBasicOptions();
        $response = $client->get(
            $endpointURL,
            $options
        );

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString($expectedCacheControlValue . ',', $response->getHeaderLine('Cache-Control'));
    }

    /**
     * @return array<string,string[]>
     */
    abstract protected function provideCacheControlEntries(): array;
}
