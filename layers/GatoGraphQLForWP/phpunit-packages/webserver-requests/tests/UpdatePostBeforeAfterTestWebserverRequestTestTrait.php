<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\ExecuteRESTWebserverRequestTestCaseTrait;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\Constants\ParamValues;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\Constants\Params;
use PHPUnitForGatoGraphQL\GatoGraphQL\Constants\RESTAPIEndpoints;

trait UpdatePostBeforeAfterTestWebserverRequestTestTrait
{
    use ExecuteRESTWebserverRequestTestCaseTrait;

    protected function executeRESTEndpointToUpdatePost(
        string $dataName,
        bool $moduleEnabled
    ): void {
        $client = static::getClient();
        $endpointURLPlaceholder = static::getWebserverHomeURL() . '/' . RESTAPIEndpoints::MODULE;
        $endpointURL = sprintf(
            $endpointURLPlaceholder,
            $this->getModuleID($dataName),
        );
        $options = $this->getRESTEndpointRequestOptions();
        $options[RequestOptions::QUERY][Params::STATE] = $moduleEnabled ? ParamValues::ENABLED : ParamValues::DISABLED;
        $response = $client->post(
            $endpointURL,
            $options,
        );
        // Assert the REST API call is successful, or already fail the test
        $this->assertRESTPostCallIsSuccessful($response);
    }

    abstract protected static function getClient(): Client;

    abstract protected function getRESTEndpointRequestOptions(): array;

    abstract protected function getModuleID(string $dataName): string;
}
