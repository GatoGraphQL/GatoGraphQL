<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use PoP\Root\Exception\ShouldNotHappenException;

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
     * @return string[]
     */
    protected function getPluginNameEntries(): array
    {
        $pluginEntries = [];
        foreach ($this->getPluginNames() as $pluginName) {
            $pluginEntries[$pluginName] = [
                'query' => $this->getPluginGraphQLQuery($pluginName),
                'response-enabled' => $this->getPluginEnabledExpectedGraphQLResponse($pluginName),
                'response-disabled' => $this->getPluginDisabledExpectedGraphQLResponse($pluginName),
            ];
        }
        return $pluginEntries;
    }

    /**
     * @return string[]
     */
    abstract protected function getPluginNames(): array;

    protected function getPluginGraphQLQuery(string $pluginName): string
    {
        $this->throwUnsupportedPluginName($pluginName);
    }

    protected function getPluginEnabledExpectedGraphQLResponse(string $pluginName): string
    {
        $this->throwUnsupportedPluginName($pluginName);
    }

    protected function getPluginDisabledExpectedGraphQLResponse(string $pluginName): string
    {
        $this->throwUnsupportedPluginName($pluginName);
    }    

    protected function throwUnsupportedPluginName(string $pluginName): never
    {
        throw new ShouldNotHappenException(
            sprintf(
                'Configuration for plugin "%s" is not complete',
                $pluginName
            )
        );
    }
}
