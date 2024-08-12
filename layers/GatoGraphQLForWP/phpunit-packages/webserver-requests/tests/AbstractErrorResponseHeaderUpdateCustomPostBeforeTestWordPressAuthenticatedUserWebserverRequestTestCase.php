<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

use GraphQLByPoP\GraphQLServer\Unit\FixtureTestCaseTrait;
use PHPUnitForGatoGraphQL\GatoGraphQL\Integration\FixtureEndpointWebserverRequestTestCaseTrait;
use PHPUnitForGatoGraphQL\WebserverRequests\Constants\CustomHeaders;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractErrorResponseHeaderUpdateCustomPostBeforeTestWordPressAuthenticatedUserWebserverRequestTestCase extends AbstractUpdateCustomPostBeforeTestWordPressAuthenticatedUserWebserverRequestTestCase
{
    use FixtureTestCaseTrait;
    use FixtureEndpointWebserverRequestTestCaseTrait;

    /**
     * @return array<string,array<mixed>>
     */
    public static function provideEndpointEntries(): array
    {
        return static::provideFixtureEndpointEntries(
            static::getFixtureFolder(),
            static::getResponseFixtureFolder()
        );
    }

    protected function mustExecuteRESTEndpointToUpdateCustomPost(string $dataName): bool
    {
        return true;
    }

    protected function validateResponseHeaders(ResponseInterface $response): void
    {
        $dataName = $this->getDataName();
        $expectedResponseErrorMessage = $this->getExpectedResponseErrorMessage($dataName);
        $this->assertEquals(
            $expectedResponseErrorMessage,
            $response->getHeaderLine(CustomHeaders::GATOGRAPHQL_ERRORS),
        );
        $expectedResponseInfoMessage = $this->getExpectedResponseInfoMessage($dataName);
        $this->assertEquals(
            $expectedResponseInfoMessage,
            $response->getHeaderLine(CustomHeaders::GATOGRAPHQL_INFO),
        );
    }

    abstract protected function getExpectedResponseErrorMessage(string $dataName): string;
    abstract protected function getExpectedResponseInfoMessage(string $dataName): string;
}
