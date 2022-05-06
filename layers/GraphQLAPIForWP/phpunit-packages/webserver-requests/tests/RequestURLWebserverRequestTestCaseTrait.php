<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

/**
 * Test that accepting a URL produces the expected status code
 */
trait RequestURLWebserverRequestTestCaseTrait
{
    /**
     * @return int|string
     */
    abstract public function dataName();

    protected function testURLRequestProducesExpectedStatusCode(
        string $clientEndpoint,
        int $expectedStatusCode,
        bool $enabled,
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
        $customHeader = $this->getCustomHeader();
        if ($customHeader !== null) {
            $hasCustomHeader = $response->hasHeader($customHeader);
            $this->assertTrue($enabled ? $hasCustomHeader : !$hasCustomHeader);
        }
    }

    protected function getCustomHeader(): ?string
    {
        return null;
    }
}
