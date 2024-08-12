<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

use GraphQLByPoP\GraphQLServer\Unit\FixtureTestCaseTrait;
use PHPUnitForGatoGraphQL\GatoGraphQL\Integration\FixtureEndpointWebserverRequestTestCaseTrait;
use PHPUnitForGatoGraphQL\WebserverRequests\Constants\CustomHeaders;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractLoggedResponseHeaderUpdateCustomPostBeforeTestWordPressAuthenticatedUserWebserverRequestTestCase extends AbstractUpdateCustomPostBeforeTestWordPressAuthenticatedUserWebserverRequestTestCase
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
        $expectedResponseLogErrorMessage = $this->getExpectedResponseLogErrorMessage($dataName);
        $this->assertEquals(
            $expectedResponseLogErrorMessage,
            $response->getHeaderLine(CustomHeaders::GATOGRAPHQL_ERRORS),
        );
        $expectedResponseLogInfoMessage = $this->getExpectedResponseLogInfoMessage($dataName);
        $this->assertEquals(
            $expectedResponseLogInfoMessage,
            $response->getHeaderLine(CustomHeaders::GATOGRAPHQL_INFO),
        );
    }

    abstract protected function getExpectedResponseLogErrorMessage(string $dataName): string;
    abstract protected function getExpectedResponseLogInfoMessage(string $dataName): string;
}
