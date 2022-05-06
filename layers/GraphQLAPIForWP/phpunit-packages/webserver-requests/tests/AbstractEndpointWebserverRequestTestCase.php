<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

abstract class AbstractEndpointWebserverRequestTestCase extends AbstractWebserverRequestTestCase
{
    /**
     * @dataProvider provideEndpointEntries
     */
    public function testEndpoints(
        string $expectedContentType,
        ?string $expectedResponseBody,
        string $endpoint,
        array $params = [],
        string $query = '',
        array $variables = [],
        string $operationName = '',
        ?string $method = null,
    ): void {
        $client = static::getClient();
        $endpointURL = static::getWebserverHomeURL() . '/' . $endpoint;
        $options = static::getRequestBasicOptions();
        if ($params !== []) {
            $options['query'] = $params;
        }
        $body = '';
        if ($query !== '' || $variables !== [] || $operationName !== '') {
            $body = json_encode([
                'query' => $query,
                'variables' => $variables,
                'operationName' => $operationName,
            ]);
        }
        if ($body !== '') {
            $options['body'] = $body;
        }

        $response = $client->request(
            $method ?? $this->getMethod(),
            $endpointURL,
            $options
        );

        $this->assertEquals($this->getExpectedResponseStatusCode(), $response->getStatusCode());
        $this->assertEquals($expectedContentType, $response->getHeaderLine('content-type'));
        if ($expectedResponseBody !== null) {
            $this->assertJsonStringEqualsJsonString($expectedResponseBody, $response->getBody()->__toString());
        }
    }

    /**
     * @return array<string,mixed>
     */
    protected static function getRequestBasicOptions(): array
    {
        $options = parent::getRequestBasicOptions();
        $options['headers']['Content-Type'] = 'application/json';
        return $options;
    }

    protected function getExpectedResponseStatusCode(): int
    {
        return 200;
    }

    /**
     * @return array<string,array<mixed>>
     */
    abstract protected function provideEndpointEntries(): array;

    protected function getMethod(): string
    {
        return 'POST';
    }
}
