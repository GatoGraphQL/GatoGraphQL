<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use PHPUnitForGraphQLAPI\GraphQLAPI\Constants\RESTAPIEndpoints;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\ExecuteRESTWebserverRequestTestCaseTrait;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Constants\Params;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Response\ResponseKeys;

trait ModifyPluginSettingsWebserverRequestTestCaseTrait
{
    use RequestRESTAPIWordPressAuthenticatedUserWebserverRequestTestTrait;
    use ExecuteRESTWebserverRequestTestCaseTrait;

    protected mixed $previousValue;

    abstract public static function assertEquals($expected, $actual, string $message = ''): void;
    abstract public static function assertNotEquals($expected, $actual, string $message = ''): void;

    protected function executePluginSettingsSetUpTearDown(string $dataName): bool
    {
        return true;
    }

    /**
     * Execute a REST API to update the client path before the test
     */
    protected function modifyPluginSettingsSetUp(): void
    {
        $dataName = $this->dataName();
        if (!$this->executePluginSettingsSetUpTearDown($dataName)) {
            return;
        }

        /**
         * First obtain and cache the previous value,
         * so it can be retrieved via REST
         * (before the modifications is carried out)
         */
        $this->previousValue = $this->getPluginSettingsOriginalValue();
        $newValue = $this->getPluginSettingsNewValue();

        // Make sure the 2 values are indeed different
        $this->assertNotEquals(
            $newValue,
            $this->previousValue,
            sprintf(
                'The new value to execute the REST API call to modify the plugin settings is \'%s\', but this is the same as the current value, and these must be different.',
                is_array($newValue) ? json_encode($newValue) : $newValue
            )
        );

        // Update the settings
        $this->executeRESTEndpointToUpdatePluginSettings(
            $dataName,
            $newValue,
        );
    }

    abstract protected function getPluginSettingsNewValue(): mixed;

    /**
     * Execute a REST API to restore the client path after the test
     */
    protected function modifyPluginSettingsTearDown(): void
    {
        $dataName = $this->dataName();
        if (!$this->executePluginSettingsSetUpTearDown($dataName)) {
            return;
        }

        $this->executeRESTEndpointToUpdatePluginSettings(
            $dataName,
            $this->previousValue,
        );
    }

    /**
     * By default, execute a REST call to obtain the current
     * value from the server
     */
    protected function getPluginSettingsOriginalValue(): mixed
    {
        $pluginSettings = $this->executeRESTEndpointToGetPluginSettings(
            $this->dataName(),
        );
        $input = $this->getSettingsKey();
        $pluginInputSettings = array_values(array_filter(
            $pluginSettings,
            fn (array $pluginSetting) => $pluginSetting[Properties::INPUT] === $input,
        ));
        $this->assertEquals(count($pluginInputSettings), 1);
        $pluginInputSetting = $pluginInputSettings[0];
        return $pluginInputSetting[ResponseKeys::VALUE];
    }

    protected function executeRESTEndpointToGetPluginSettings(
        string $dataName,
    ): array {
        $client = static::getClient();
        $endpointURLPlaceholder = static::getWebserverHomeURL() . '/' . RESTAPIEndpoints::MODULE_SETTINGS;
        $endpointURL = sprintf(
            $endpointURLPlaceholder,
            $this->getModuleID($dataName),
        );
        $options = $this->getRESTEndpointRequestOptions();
        $response = $client->get(
            $endpointURL,
            $options,
        );
        // Assert the REST API call is successful, or already fail the test
        $this->assertRESTGetCallIsSuccessful($response);
        $endpointResponse = json_decode($response->getBody()->__toString(), true);
        return $endpointResponse[ResponseKeys::SETTINGS];
    }

    protected function executeRESTEndpointToUpdatePluginSettings(
        string $dataName,
        mixed $value,
    ): void {
        $client = static::getClient();
        $endpointURLPlaceholder = static::getWebserverHomeURL() . '/' . RESTAPIEndpoints::MODULE_SETTINGS;
        $endpointURL = sprintf(
            $endpointURLPlaceholder,
            $this->getModuleID($dataName),
        );
        $options = $this->getRESTEndpointRequestOptions();
        $options['query'][Params::JSON_ENCODED_OPTION_VALUES] = json_encode([
            $this->getSettingsKey() => $value,
        ]);
        $response = $client->post(
            $endpointURL,
            $options,
        );
        // Assert the REST API call is successful, or already fail the test
        $this->assertRESTPostCallIsSuccessful($response);
    }

    abstract protected function getSettingsKey(): string;

    /**
     * To visualize the list of all the modules, and find the "moduleID":
     *
     * @see https://graphql-api.lndo.site/wp-json/graphql-api/v1/admin/modules
     */
    abstract protected function getModuleID(string $dataName): string;
}
