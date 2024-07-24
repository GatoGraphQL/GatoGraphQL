<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

use GuzzleHttp\RequestOptions;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants\Actions;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants\Params;
use PoP\ComponentModel\Misc\GeneralUtils;
use RuntimeException;

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
        $isModuleEnabledByDefault = $this->isModuleEnabledByDefault($dataName);
        $isPluginActiveByDefault = $this->isPluginActiveByDefault($dataName);
        $pluginName = $this->getPluginNameFromDataName($dataName);
        if ($isModuleEnabledByDefault && str_ends_with($dataName, ':disabled') && $isPluginActiveByDefault) {
            $this->executeRESTEndpointToEnableOrDisablePlugin($pluginName, 'inactive');
        } elseif ((!$isModuleEnabledByDefault || !$isPluginActiveByDefault) && str_ends_with($dataName, ':enabled')) {
            $this->executeRESTEndpointToEnableOrDisablePlugin($pluginName, 'active');
        } elseif (str_ends_with($dataName, ':only-one-enabled')) {
            $this->executeEndpointToBulkDeactivatePlugins(
                $this->getBulkPluginDeactivationPluginFilesToSkip($dataName)
            );
            if (!$isModuleEnabledByDefault) {
                $this->executeRESTEndpointToEnableOrDisablePlugin($pluginName, 'active');
            }
        }
    }

    protected function tearDown(): void
    {
        /**
         * Re-enable the plugin after executing the ":disabled" test
         */
        $dataName = $this->getDataName();
        $isModuleEnabledByDefault = $this->isModuleEnabledByDefault($dataName);
        $isPluginActiveByDefault = $this->isPluginActiveByDefault($dataName);
        $pluginName = $this->getPluginNameFromDataName($dataName);
        if ($isModuleEnabledByDefault && str_ends_with($dataName, ':disabled') && $isPluginActiveByDefault) {
            $this->executeRESTEndpointToEnableOrDisablePlugin($pluginName, 'active');
        } elseif ((!$isModuleEnabledByDefault || !$isPluginActiveByDefault) && str_ends_with($dataName, ':enabled')) {
            $this->executeRESTEndpointToEnableOrDisablePlugin($pluginName, 'inactive');
        } elseif (str_ends_with($dataName, ':only-one-enabled')) {
            $this->executeEndpointToBulkActivatePlugins();
            if (!$isModuleEnabledByDefault) {
                $this->executeRESTEndpointToEnableOrDisablePlugin($pluginName, 'inactive');
            }
        }

        parent::tearDown();
    }

    protected function isModuleEnabledByDefault(string $dataName): bool
    {
        return true;
    }

    protected function isPluginActiveByDefault(string $dataName): bool
    {
        return true;
    }

    /**
     * @return array<string,array<mixed>>
     */
    public static function provideEndpointEntries(): array
    {
        $endpoint = static::getEndpoint();
        $providerEntries = [];
        foreach (static::getPluginNameEntries() as $pluginName => $pluginEntry) {
            // Only for the variations, the "enable" and "disable" responses are optional
            if (isset($pluginEntry['response-enabled'])) {
                $providerEntries[$pluginName . ':enabled'] = [
                    'application/json',
                    $pluginEntry['response-enabled'],
                    $endpoint,
                    [],
                    $pluginEntry['query'],
                    $pluginEntry['variables'] ?? [],
                ];
            }
            if (isset($pluginEntry['response-disabled'])) {
                $providerEntries[$pluginName . ':disabled'] = [
                    'application/json',
                    $pluginEntry['response-disabled'],
                    $endpoint,
                    [],
                    $pluginEntry['query'],
                    $pluginEntry['variables'] ?? [],
                ];
            }
            if (isset($pluginEntry['response-only-one-enabled'])) {
                $providerEntries[$pluginName . ':only-one-enabled'] = [
                    'application/json',
                    $pluginEntry['response-only-one-enabled'],
                    $endpoint,
                    [],
                    $pluginEntry['query'],
                    $pluginEntry['variables'] ?? [],
                ];
            }
        }
        return $providerEntries;
    }

    protected static function getEndpoint(): string
    {
        return 'wp-admin/edit.php?page=gatographql&action=execute_query';
    }

    /**
     * @return array<string,array<string,mixed>> An array of [$pluginName => ['query' => "...", 'response-enabled' => "...", 'response-disabled' => "..."]]
     */
    abstract protected static function getPluginNameEntries(): array;

    /**
     * @see https://developer.wordpress.org/rest-api/using-the-rest-api/authentication/
     */
    protected function executeRESTEndpointToEnableOrDisablePlugin(string $pluginName, string $status): void
    {
        $client = static::getClient();
        $restEndpointPlaceholder = 'wp-json/wp/v2/plugins/%s/?status=%s';
        $endpointURLPlaceholder = static::getWebserverHomeURL() . '/' . $restEndpointPlaceholder;
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
     * The tests have this shape:
     *
     *   pluginVendor/pluginSlug/fixtureName.gql
     *
     * pluginName = pluginVendor/pluginSlug
     */
    protected function getPluginNameFromDataName(string $dataName): string
    {
        $parts = explode('/', $dataName);
        if (count($parts) < 3) {
            throw new RuntimeException(
                sprintf('DataName \'%s\' must have shape pluginVendor/pluginSlug/fixtureName', $dataName)
            );
        }
        return $parts[0] . '/' . $parts[1];
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
        $pluginName = $this->getPluginNameFromDataName($dataName);
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
        $options = static::getRESTEndpointRequestOptions();
        /**
         * If "query" is set in the options, it will override
         * the params in the URL, so transfer them there
         */
        if (isset($options[RequestOptions::QUERY])) {
            /** @var array<string,mixed> */
            $optionsQuery = $options[RequestOptions::QUERY];
            $endpoint = GeneralUtils::addQueryArgs(
                $optionsQuery,
                $endpoint
            );
            unset($options[RequestOptions::QUERY]);
        }
        $client->post(
            $endpoint,
            $options
        );
    }
}
