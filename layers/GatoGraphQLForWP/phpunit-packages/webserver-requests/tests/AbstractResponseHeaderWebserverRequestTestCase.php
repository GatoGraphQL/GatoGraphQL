<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

use PHPUnit\Framework\Attributes\DataProvider;

abstract class AbstractResponseHeaderWebserverRequestTestCase extends AbstractWebserverRequestTestCase
{
    #[DataProvider('provideResponseHeaderEntries')]
    public function testResponseHeader(
        string $endpoint,
        string $expectedResponseHeaderValue,
    ): void {
        $client = static::getClient();
        $endpointURL = static::getWebserverHomeURL() . '/' . $endpoint;
        $options = static::getRequestBasicOptions();
        $response = $client->get(
            $endpointURL,
            $options
        );

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString($expectedResponseHeaderValue, $response->getHeaderLine($this->getHeaderName()));
    }

    abstract protected function getHeaderName(): string;

    /**
     * @return array<string,string[]>
     */
    abstract public static function provideResponseHeaderEntries(): array;
}
