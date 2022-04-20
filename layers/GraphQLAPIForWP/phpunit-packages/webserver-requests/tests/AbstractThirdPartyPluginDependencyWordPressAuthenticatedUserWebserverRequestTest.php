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

    /**
     * @return array<string,array<mixed>>
     */
    protected function provideEndpointEntries(): array
    {
        $endpoint = 'wp-admin/edit.php?page=graphql_api&action=execute_query';
        $entries = [];
        foreach ($this->getPluginNames() as $pluginName) {
            $query = $this->getPluginGraphQLQuery($pluginName);
            $entries[$pluginName . '-enabled'] = [
                'application/json',
                $this->getPluginEnabledExpectedGraphQLResponse($pluginName),
                $endpoint,
                [],
                $query,
            ];
            $entries[$pluginName . '-disabled'] = [
                'application/json',
                $this->getPluginDisabledExpectedGraphQLResponse($pluginName),
                $endpoint,
                [],
                $query,
            ];
        }
        return $entries;
    }

    /**
     * @return string[]
     */
    abstract protected function getPluginNames(): array;
    abstract protected function getPluginGraphQLQuery(string $pluginName): string;
    abstract protected function getPluginEnabledExpectedGraphQLResponse(string $pluginName): string;
    abstract protected function getPluginDisabledExpectedGraphQLResponse(string $pluginName): string;
}
