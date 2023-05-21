<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants\Actions;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants\Params;
use PoP\ComponentModel\Misc\GeneralUtils;

/**
 * Test that enabling/disabling a required 3rd-party plugin works well.
 *
 * It uses the REST API to disable/enable the plugin before/after executing
 * the test. That's why these tests are done with the authenticated user
 * in WordPress, so the user can execute operations via the REST endpoint.
 */
abstract class AbstractThirdPartyPluginDependencyWordPressAuthenticatedUserWebserverRequestTestCase extends AbstractEndpointWebserverRequestTestCase
{
    use RequestRESTAPIWordPressAuthenticatedUserWebserverRequestTestTrait;

    protected function setUp(): void
    {
        parent::setUp();

        /**
         * Disable the plugin before executing the ":disabled" test
         */
        $dataName = $this->getDataName();
        if (str_ends_with($dataName, ':disabled')) {
            $this->executeRESTEndpointToEnableOrDisablePlugin($dataName, 'inactive');
        } elseif (str_ends_with($dataName, ':only-one-enabled')) {
            $this->executeEndpointToBulkDeactivatePlugins(
                $this->getBulkPluginDeactivationPluginFilesToSkip($dataName)
            );
        }
    }

    protected function tearDown(): void
    {
        /**
         * Re-enable the plugin after executing the ":disabled" test
         */
        $dataName = $this->getDataName();
        if (str_ends_with($dataName, ':disabled')) {
            $this->executeRESTEndpointToEnableOrDisablePlugin($dataName, 'active');
        } elseif (str_ends_with($dataName, ':only-one-enabled')) {
            $this->executeEndpointToBulkActivatePlugins();
        }

        parent::tearDown();
    }

    /**
     * @return array<string,array<mixed>>
     */
    protected function provideEndpointEntries(): array
    {
        $endpoint = $this->getEndpoint();
        $providerEntries = [];
        foreach ($this->getPluginNameEntries() as $pluginName => $pluginEntry) {
            $providerEntries[$pluginName . ':enabled'] = [
                'application/json',
                $pluginEntry['response-enabled'],
                $endpoint,
                [],
                $pluginEntry['query'],
            ];
            $providerEntries[$pluginName . ':disabled'] = [
                'application/json',
                $pluginEntry['response-disabled'],
                $endpoint,
                [],
                $pluginEntry['query'],
            ];
            if (isset($pluginEntry['response-only-one-enabled'])) {
                $providerEntries[$pluginName . ':only-one-enabled'] = [
                    'application/json',
                    $pluginEntry['response-only-one-enabled'],
                    $endpoint,
                    [],
                    $pluginEntry['query'],
                ];
            }
        }
        return $providerEntries;
    }

    protected function getEndpoint(): string
    {
        return 'wp-admin/edit.php?page=gato_graphql&action=execute_query';
    }

    /**
     * @return array<string,array<string,mixed>> An array of [$pluginName => ['query' => "...", 'response-enabled' => "...", 'response-disabled' => "..."]]
     */
    abstract protected function getPluginNameEntries(): array;

    /**
     * @see https://developer.wordpress.org/rest-api/using-the-rest-api/authentication/
     */
    protected function executeRESTEndpointToEnableOrDisablePlugin(string $dataName, string $status): void
    {
        $client = static::getClient();
        $restEndpointPlaceholder = 'wp-json/wp/v2/plugins/%s/?status=%s';
        $endpointURLPlaceholder = static::getWebserverHomeURL() . '/' . $restEndpointPlaceholder;
        $pluginName = substr($dataName, 0, strlen($dataName) - strlen(':disabled'));
        $client->post(
            sprintf(
                $endpointURLPlaceholder,
                $pluginName,
                $status
            ),
            static::getRESTEndpointRequestOptions()
        );
    }

    /**
     * @param string[] $skipDeactivatingPluginFiles
     */
    protected function executeEndpointToBulkDeactivatePlugins(
        array $skipDeactivatingPluginFiles,
    ): void {
        $endpointParams = [
            'actions' => [
                Actions::EXECUTE_BULK_PLUGIN_DEACTIVATION,
            ],
            Params::SKIP_DEACTIVATING_PLUGIN_FILES => $skipDeactivatingPluginFiles,
        ];
        $this->executeEndpointViaParamsAgainstWPAdmin($endpointParams);
    }

    /**
     * @return string[]
     */
    protected function getBulkPluginDeactivationPluginFilesToSkip(string $dataName): array
    {
        $pluginName = substr($dataName, 0, strlen($dataName) - strlen(':only-one-enabled'));
        $pluginFile = $pluginName . '.php';
        return [
            $pluginFile,
        ];
    }

    protected function executeEndpointToBulkActivatePlugins(): void
    {
        $endpointParams = [
            'actions' => [
                Actions::EXECUTE_BULK_PLUGIN_ACTIVATION,
            ],
        ];
        $this->executeEndpointViaParamsAgainstWPAdmin($endpointParams);
    }

    /**
     * @param array<string,mixed> $endpointParams
     */
    protected function executeEndpointViaParamsAgainstWPAdmin(
        array $endpointParams,
    ): void {
        $client = static::getClient();
        $endpoint = GeneralUtils::addQueryArgs(
            $endpointParams,
            static::getWebserverHomeURL() . '/' . 'wp-admin/index.php'
        );
        $client->post(
            $endpoint,
            static::getRESTEndpointRequestOptions()
        );
    }
}
