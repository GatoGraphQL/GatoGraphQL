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
        $expectedResponseLogErrorMessageOrRegex = $this->getExpectedResponseLogErrorMessageOrRegex($dataName);
        if (str_starts_with($expectedResponseLogErrorMessageOrRegex, '/') && str_ends_with($expectedResponseLogErrorMessageOrRegex, '/')) {
            $expectedResponseLogErrorMessageRegex = $expectedResponseLogErrorMessageOrRegex;
            $this->assertMatchesRegularExpression(
                $expectedResponseLogErrorMessageRegex,
                $response->getHeaderLine(CustomHeaders::GATOGRAPHQL_ERRORS),
            );
        } else {
            $expectedResponseLogErrorMessage = $expectedResponseLogErrorMessageOrRegex;
            $this->assertEquals(
                $expectedResponseLogErrorMessage,
                $response->getHeaderLine(CustomHeaders::GATOGRAPHQL_ERRORS),
            );
        }

        $expectedResponseLogInfoMessageOrRegex = $this->getExpectedResponseLogInfoMessageOrRegex($dataName);
        if (str_starts_with($expectedResponseLogInfoMessageOrRegex, '/') && str_ends_with($expectedResponseLogInfoMessageOrRegex, '/')) {
            $expectedResponseLogInfoMessageRegex = $expectedResponseLogInfoMessageOrRegex;
            $this->assertMatchesRegularExpression(
                $expectedResponseLogInfoMessageRegex,
                $response->getHeaderLine(CustomHeaders::GATOGRAPHQL_INFO),
            );
        } else {
            $expectedResponseLogInfoMessage = $expectedResponseLogInfoMessageOrRegex;
            $this->assertEquals(
                $expectedResponseLogInfoMessage,
                $response->getHeaderLine(CustomHeaders::GATOGRAPHQL_INFO),
            );
        }
    }

    abstract protected function getExpectedResponseLogErrorMessageOrRegex(string $dataName): string;
    abstract protected function getExpectedResponseLogInfoMessageOrRegex(string $dataName): string;
}
