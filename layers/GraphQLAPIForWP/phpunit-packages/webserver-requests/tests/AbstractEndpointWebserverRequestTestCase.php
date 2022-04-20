<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use GuzzleHttp\Exception\ClientException;

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
        ?string $method = null,
    ): void {
        /**
         * Allow to execute a REST endpoint against the webserver
         * before running the test
         */
        $this->beforeRunningTest($this->dataName());

        $client = static::getClient();
        $endpointURL = static::getWebserverHomeURL() . '/' . $endpoint;
        $options = static::getRequestBasicOptions();
        if ($params !== []) {
            $options['query'] = $params;
        }
        $body = '';
        if ($query !== '' || $variables !== []) {
            $body = json_encode([
                'query' => $query,
                'variables' => $variables,
            ]);
        }
        if ($body !== '') {
            $options['body'] = $body;
        }
        try {
            $response = $client->request(
                $method ?? $this->getMethod(),
                $endpointURL,
                $options
            );
        } catch (ClientException $e) {
            /**
             * A 404 is a Client Exception.
             * It's a failure, not an error.
             */
            $this->fail($e->getMessage());
        }

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expectedContentType, $response->getHeaderLine('content-type'));
        if ($expectedResponseBody !== null) {
            $this->assertJsonStringEqualsJsonString($expectedResponseBody, $response->getBody()->__toString());
        }

        /**
         * Allow to execute a REST endpoint against the webserver
         * after running the test
         */
        $this->afterRunningTest($this->dataName());
    }

    /**
     * @return array<string,mixed>
     */
    protected static function getRequestBasicOptions(): array
    {
        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ];
        if (static::shareCookies()) {
            $options['cookies'] = self::$cookieJar;
        }
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
    protected function beforeRunningTest(string $dataName): void
    {
        // Override if needed
    }

    /**
     * Allow to execute a REST endpoint against the webserver
     * after running the test
     */
    protected function afterRunningTest(string $dataName): void
    {
        // Override if needed
    }
}
