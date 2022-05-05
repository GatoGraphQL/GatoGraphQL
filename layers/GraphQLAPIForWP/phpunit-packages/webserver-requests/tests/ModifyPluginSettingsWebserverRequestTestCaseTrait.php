<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use PHPUnitForGraphQLAPI\GraphQLAPI\Constants\RESTAPIEndpoints;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\ExecuteRESTWebserverRequestTestCaseTrait;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Constants\Params;

trait ModifyPluginSettingsWebserverRequestTestCaseTrait
{
    use RequestRESTAPIWordPressAuthenticatedUserWebserverRequestTestTrait;
    use ExecuteRESTWebserverRequestTestCaseTrait;

    protected function modifyPluginSettingsSetUp(): void
    {
        /**
         * Execute a REST API to update the client path before the test
         */
        $dataName = $this->dataName();
        $newValue = $this->getPluginSettingsNewValue();
        $this->executeRESTEndpointToUpdatePluginSettings($dataName, $newValue);
    }

    abstract protected function getPluginSettingsNewValue(): mixed;

    protected function modifyPluginSettingsTearDown(): void
    {
        /**
         * Execute a REST API to restore the client path after the test
         */
        $dataName = $this->dataName();
        $previousValue = $this->getPluginSettingsOriginalValue();
        $this->executeRESTEndpointToUpdatePluginSettings($dataName, $previousValue);
    }

    abstract protected function getPluginSettingsOriginalValue(): mixed;

    protected function executeRESTEndpointToUpdatePluginSettings(
        string $dataName,
        mixed $value
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
