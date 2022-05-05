<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use PHPUnitForGraphQLAPI\GraphQLAPI\Constants\RESTAPIEndpoints;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\ExecuteRESTWebserverRequestTestCaseTrait;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Constants\Params;

trait ModifyPluginSettingsWebserverRequestTestTrait
{
    use RequestRESTAPIWordPressAuthenticatedUserWebserverRequestTestTrait;
    use ExecuteRESTWebserverRequestTestCaseTrait;

    protected function modifyPluginSettingsSetUp(): void
    {
        /**
         * Execute a REST API to update the client path before the test
         */
        $dataName = $this->dataName();
        $data = $this->getProvidedData();
        $newValue = $data[0];
        $this->executeRESTEndpointToUpdatePluginSettings($dataName, $newValue);
    }

    protected function modifyPluginSettingsTearDown(): void
    {
        /**
         * Execute a REST API to restore the client path after the test
         */
        $dataName = $this->dataName();
        $data = $this->getProvidedData();
        $previousValue = $data[1];
        $this->executeRESTEndpointToUpdatePluginSettings($dataName, $previousValue);
    }

    protected function executeRESTEndpointToUpdatePluginSettings(
        string $dataName,
        string $value
    ): void {
        $client = static::getClient();
        $endpointURLPlaceholder = static::getWebserverHomeURL() . '/' . RESTAPIEndpoints::MODULE_SETTINGS;
        $endpointURL = sprintf(
            $endpointURLPlaceholder,
            $this->getModuleID($dataName),
        );
        $options = $this->getRESTEndpointRequestOptions();
        $options['query'][Params::OPTION_VALUES] = [
            $this->getSettingsKey() => $value,
        ];
        $response = $client->post(
            $endpointURL,
            $options,
        );
        // Assert the REST API call is successful, or already fail the test
        $this->assertRESTCallIsSuccessful($response);
    }

    abstract protected function getSettingsKey(): string;

    /**
     * To visualize the list of all the modules, and find the "moduleID":
     *
     * @see http://graphql-api.lndo.site/wp-json/graphql-api/v1/admin/modules
     */
    abstract protected function getModuleID(string $dataName): string;
}
