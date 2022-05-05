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

    protected mixed $previousValue;

    /**
     * Execute a REST API to update the client path before the test
     */
    protected function modifyPluginSettingsSetUp(): void
    {
        /**
         * First obtain and cache the previous value,
         * so it can be retrieved via REST
         * (before the modifications is carried out)
         */
        $this->previousValue = $this->getPluginSettingsOriginalValue();
        $this->executeRESTEndpointToUpdatePluginSettings(
            $this->dataName(),
            $this->getPluginSettingsNewValue(),
        );
    }

    abstract protected function getPluginSettingsNewValue(): mixed;

    /**
     * Execute a REST API to restore the client path after the test
     */
    protected function modifyPluginSettingsTearDown(): void
    {
        $this->executeRESTEndpointToUpdatePluginSettings(
            $this->dataName(),
            $this->previousValue,
        );
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
