<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting;

use PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\Constants\ResponseStatus;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\RESTResponse;
use Psr\Http\Message\ResponseInterface;

trait ExecuteRESTWebserverRequestTestCaseTrait
{
    abstract public static function assertEquals($expected, $actual, string $message = ''): void;
    abstract public static function assertStringStartsWith(string $prefix, string $string, string $message = ''): void;

    /**
     * Assert the REST API call is successful, or already fail the test
     */
    protected function assertRESTGetCallIsSuccessful(
        ResponseInterface $response
    ): void {
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringStartsWith('application/json', $response->getHeaderLine('content-type'));
    }

    /**
     * Assert the REST API call is successful, or already fail the test
     *
     * @param array<string,mixed> $options
     */
    protected function assertRESTPostCallIsSuccessful(
        ResponseInterface $response,
        string $dataName,
        string $endpointURL,
        array $options = [],
    ): void {
        $this->assertRESTGetCallIsSuccessful($response);
        $restResponse = RESTResponse::fromClientResponse($response);
        $this->assertEquals($this->getRESTCallSuccessStatus($dataName, $endpointURL, $options), $restResponse->status);
    }

    /**
     * @param array<string,mixed> $options
     */
    protected function getRESTCallSuccessStatus(
        string $dataName,
        string $endpointURL,
        array $options = [],
    ): string {
        return ResponseStatus::SUCCESS;
    }
}
