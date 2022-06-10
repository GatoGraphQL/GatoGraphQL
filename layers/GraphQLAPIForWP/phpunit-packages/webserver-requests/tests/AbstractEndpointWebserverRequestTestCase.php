<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use PoP\ComponentModel\Misc\GeneralUtils;

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
            $params = $this->maybeAddXDebugTriggerParam($params);
            $options['query'] = $params;
        } else {
            $endpointURL = $this->maybeAddXDebugTriggerParam($endpointURL);
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
        $this->assertStringStartsWith($expectedContentType, $response->getHeaderLine('content-type'));
        if ($expectedResponseBody !== null) {
            $this->assertJsonStringEqualsJsonString($expectedResponseBody, $response->getBody()->__toString());
        }
    }

    protected static function maybeAddXDebugTriggerParam(string|array $urlOrParams): string|array
    {
        if (getenv('XDEBUG_TRIGGER') === false) {
            return $urlOrParams;
        }
        $xdebugParams = [
            'XDEBUG_TRIGGER' => getenv('XDEBUG_TRIGGER'),
        ];
        if (is_array($urlOrParams)) {
            /** @var array */
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
