<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use GuzzleHttp\RequestOptions;
use PoP\ComponentModel\Misc\GeneralUtils;
use RuntimeException;

abstract class AbstractEndpointWebserverRequestTestCase extends AbstractWebserverRequestTestCase
{
    /**
     * @dataProvider provideEndpointEntries
     * @param array<string,mixed> $params
     * @param array<string,mixed> $variables
     */
    public function testEndpoints(
        string $expectedContentType,
        ?string $expectedResponseBody,
        string $endpoint,
        array $params = [],
        string $query = '',
        array $variables = [],
        ?string $operationName = null,
        ?string $method = null,
    ): void {
        $method ??= $this->getMethod();
        if (!in_array($method, ["POST", "GET"])) {
            throw new RuntimeException(sprintf(
                'Unsupported method \'%s\' for testing a GraphQL endpoint',
                $method
            ));
        }

        $client = static::getClient();
        $endpointURL = static::getWebserverHomeURL() . '/' . $endpoint;
        $options = static::getRequestBasicOptions();
        if ($params !== [] || $method === "GET") {
            /** @var array<string,mixed> */
            $params = $this->maybeAddXDebugTriggerParam($params);
            $options[RequestOptions::QUERY] = $params;
        } else {
            /** @var string */
            $endpointURL = $this->maybeAddXDebugTriggerParam($endpointURL);
        }

        if ($method === "POST") {
            $options[RequestOptions::BODY] = json_encode([
                'query' => $query,
                'variables' => $variables,
                'operationName' => $operationName ?? '',
            ]);
        } elseif ($method === "GET") {
            $options[RequestOptions::QUERY] = array_merge(
                $options[RequestOptions::QUERY] ?? [],
                [
                    'query' => $query,
                    'variables' => $variables,
                    'operationName' => $operationName ?? '',
                ]
            );
        }

        $response = $client->request(
            $method,
            $endpointURL,
            $options
        );

        $this->assertEquals($this->getExpectedResponseStatusCode(), $response->getStatusCode());
        $this->assertStringStartsWith($expectedContentType, $response->getHeaderLine('content-type'));
        if ($expectedResponseBody !== null) {
            $this->assertJsonStringEqualsJsonString($expectedResponseBody, $response->getBody()->__toString());
        }
    }

    /**
     * @param string|array<string,mixed> $urlOrParams
     * @return string|array<string,mixed>
     */
    protected static function maybeAddXDebugTriggerParam(string|array $urlOrParams): string|array
    {
        if (getenv('XDEBUG_TRIGGER') === false) {
            return $urlOrParams;
        }
        $xdebugParams = [
            'XDEBUG_TRIGGER' => getenv('XDEBUG_TRIGGER'),
        ];
        if (is_array($urlOrParams)) {
            /** @var string[] */
            $params = $urlOrParams;
            return array_merge(
                $params,
                $xdebugParams
            );
        }
        /** @var string */
        $url = $urlOrParams;
        return GeneralUtils::addQueryArgs($xdebugParams, $url);
    }

    /**
     * @return array<string,mixed>
     */
    protected static function getRequestBasicOptions(): array
    {
        $options = parent::getRequestBasicOptions();
        $options[RequestOptions::HEADERS]['Content-Type'] = 'application/json';
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
