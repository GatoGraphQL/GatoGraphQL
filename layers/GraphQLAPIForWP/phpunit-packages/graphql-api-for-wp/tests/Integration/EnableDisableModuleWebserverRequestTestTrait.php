<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GuzzleHttp\Client;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Constants\ParamValues;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Constants\Params;
use PHPUnitForGraphQLAPI\GraphQLAPI\Constants\RESTAPIEndpoints;

trait EnableDisableModuleWebserverRequestTestTrait
{
    protected function enableOrDisableModule(
        string $dataName,
    ): void {
        /**
         * To test their disabled state works well, first execute
         * a REST API call to disable the client, and then re-enable
         * it afterwards.
         */
        if (str_ends_with($dataName, '@disabled')) {
            $this->executeRESTEndpointToEnableOrDisableModule($dataName, false);
            return;
        }
        /**
         * Re-enable the clients for the single endpoint
         * after the "disabled" test
         */
        if (str_ends_with($dataName, '@enabled')) {
            $this->executeRESTEndpointToEnableOrDisableModule($dataName, true);
        }
    }

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

    abstract protected function assertRESTPostCallIsSuccessful();
}
