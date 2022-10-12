<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use GuzzleHttp\Client;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\ExecuteRESTWebserverRequestTestCaseTrait;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Constants\ParamValues;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Constants\Params;
use PHPUnitForGraphQLAPI\GraphQLAPI\Constants\RESTAPIEndpoints;

trait EnableDisableModuleWebserverRequestTestTrait
{
    use ExecuteRESTWebserverRequestTestCaseTrait;

    protected function executeRESTEndpointToEnableOrDisableModule(
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
        $options['query'][Params::STATE] = $moduleEnabled ? ParamValues::ENABLED : ParamValues::DISABLED;
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
