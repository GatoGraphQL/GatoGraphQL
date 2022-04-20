<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

/**
 * Test that enabling/disabling a required 3rd-party plugin works well.
 *
 * It uses the REST API to disable/enable the plugin before/after executing
 * the test. That's why these tests are done with the authenticated user
 * in WordPress, so the user can execute operations via the REST endpoint.
 */
abstract class AbstractThirdPartyPluginDependencyWordPressAuthenticatedUserWebserverRequestTest extends AbstractEndpointWebserverRequestTestCase
{
    use WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

    protected static function useSSL(): bool
    {
        return true;
    }

    /**
     * @return array<string,array<mixed>>
     */
    protected function provideEndpointEntries(): array
    {
        $endpoint = 'wp-admin/edit.php?page=graphql_api&action=execute_query';
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
        }
        return $providerEntries;
    }

    /**
     * @return array<string,array<string>> An array of [$pluginName => ['query' => "...", 'response-enabled' => "...", 'response-disabled' => "..."]]
     */
    abstract protected function getPluginNameEntries(): array;

    /**
     * Disable the plugin before executing the ":disabled" test
     */
    protected function beforeRunningTest(string $dataName): void
    {
        if (str_ends_with($dataName, ':disabled')) {
            $this->executeRESTEndpointToEnableOrDisablePlugin($dataName, 'inactive');
        }
        parent::beforeRunningTest($dataName);
    }

    /**
     * @see https://developer.wordpress.org/rest-api/using-the-rest-api/authentication/
     */
    protected function executeRESTEndpointToEnableOrDisablePlugin(string $dataName, string $status): void
    {
        $client = static::getClient();
        $restEndpointPlaceholder = 'wp-json/wp/v2/plugins/%s/?status=%s';
        $endpointURLPlaceholder = static::getWebserverHomeURL() . '/' . $restEndpointPlaceholder;
        $options = static::getRESTEndpointRequestOptions();
        $pluginName = substr($dataName, 0, strlen($dataName) - strlen(':disabled'));
        $client->post(
            sprintf(
                $endpointURLPlaceholder,
                $pluginName,
                $status
            ),
            $options
        );
    }

    /**
     * Must add the X-WP-Nonce header for the authenticated user.
     *
     * @see https://developer.wordpress.org/rest-api/using-the-rest-api/authentication/
     */
    protected function getRESTEndpointRequestOptions(): array
    {
        $options = static::getRequestBasicOptions();
        $options['headers']['X-WP-Nonce'] = static::$wpRESTNonce;
        return $options;
    }

    /**
     * Re-enable the plugin after executing the ":disabled" test
     */
    protected function afterRunningTest(string $dataName): void
    {
        if (str_ends_with($dataName, ':disabled')) {
            $this->executeRESTEndpointToEnableOrDisablePlugin($dataName, 'active');
        }
        parent::afterRunningTest($dataName);
    }
}
