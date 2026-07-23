<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;
use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;
use GuzzleHttp\RequestOptions;
use PHPUnitForGatoGraphQL\GatoGraphQL\Constants\RESTAPIEndpoints;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\Constants\Params;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\RESTAPI\Response\ResponseKeys;
use PoPSchema\SchemaCommons\Constants\Behaviors;

/**
 * The `optionNames`/`options` fields require both the "entries" and the
 * "behavior" settings to be configured to produce a deterministic response
 * (otherwise, under the default "deny" behavior with no entries, `optionNames`
 * returns every option stored in the DB). Hence this test case modifies both
 * settings at once, instead of only one as `AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase`.
 */
abstract class AbstractOptionsModifyPluginSettingsFixtureEndpointWebserverRequestTestCase extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected function getSettingsKey(): string
    {
        return ModuleSettingOptions::ENTRIES;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'gatographql_gatographql_schema-settings';
    }

    /**
     * @return string[]
     */
    abstract protected function getAllowedOptionEntries(): array;

    protected function getOptionAccessBehavior(): string
    {
        return Behaviors::ALLOW;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getPluginSettingsNewValue(): mixed
    {
        return [
            ModuleSettingOptions::ENTRIES => $this->getAllowedOptionEntries(),
            ModuleSettingOptions::BEHAVIOR => $this->getOptionAccessBehavior(),
        ];
    }

    /**
     * @return array<string,mixed>
     */
    protected function getPluginSettingsOriginalValue(): mixed
    {
        $pluginSettings = $this->executeRESTEndpointToGetPluginSettings(
            $this->getDataName(),
        );
        $values = [];
        foreach ([ModuleSettingOptions::ENTRIES, ModuleSettingOptions::BEHAVIOR] as $input) {
            $pluginInputSettings = array_values(array_filter(
                $pluginSettings,
                fn (array $pluginSetting) => $pluginSetting[Properties::INPUT] === $input,
            ));
            $this->assertEquals(count($pluginInputSettings), 1);
            $values[$input] = $pluginInputSettings[0][ResponseKeys::VALUE];
        }
        return $values;
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
        $options[RequestOptions::QUERY][Params::JSON_ENCODED_OPTION_VALUES] = json_encode($value);
        $response = $client->post(
            $endpointURL,
            $options,
        );
        $this->assertRESTPostCallIsSuccessful($response, $dataName, $endpointURL, $options);
    }
}
