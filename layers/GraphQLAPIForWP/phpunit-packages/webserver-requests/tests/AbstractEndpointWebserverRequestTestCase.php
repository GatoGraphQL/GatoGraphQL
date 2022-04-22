<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use Exception;

// use GuzzleHttp\Exception\ClientException;

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

        $dataName = $this->dataName();
        // Set-up: Allow to execute a REST endpoint against the webserver
        $this->beforeFixtureClientRequest($dataName);
        $exception = null;
        try {
            $response = $client->request(
                $method ?? $this->getMethod(),
                $endpointURL,
                $options
            );
        } catch (Exception $e) {
            // // A 404 is a Client Exception. It's a failure, not an error.
            // if ($e instanceof ClientException) {
            //     $this->fail($e->getMessage());
            // }
            $exception = $e;
        } finally {
            // Clean-up: Allow to execute a REST endpoint against the webserver
            $this->afterFixtureClientRequest($dataName);
        }
        if ($exception !== null) {
            throw $exception;
        }

        $this->assertEquals(200, $response->getStatusCode());
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

    /**
     * @return array<string,array<mixed>>
     */
    abstract protected function provideEndpointEntries(): array;

    protected function getMethod(): string
    {
        return 'POST';
    }

    /**
     * Allow to execute a REST endpoint against the webserver
     * before running the test
     */
    protected function beforeFixtureClientRequest(string $dataName): void
    {
        // Override if needed
    }

    /**
     * Allow to execute a REST endpoint against the webserver
     * after running the test
     */
    protected function afterFixtureClientRequest(string $dataName): void
    {
        // Override if needed
    }
}
