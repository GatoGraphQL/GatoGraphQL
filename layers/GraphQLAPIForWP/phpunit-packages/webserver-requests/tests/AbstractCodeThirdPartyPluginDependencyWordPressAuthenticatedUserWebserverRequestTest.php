<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use PoP\Root\Exception\ShouldNotHappenException;

/**
 * Test that enabling/disabling a required 3rd-party plugin works well.
 */
abstract class AbstractCodeThirdPartyPluginDependencyWordPressAuthenticatedUserWebserverRequestTest extends AbstractThirdPartyPluginDependencyWordPressAuthenticatedUserWebserverRequestTest
{
    /**
     * @return array<string,array<string>> An array of [$pluginName => ['query' => "...", 'response-enabled' => "...", 'response-disabled' => "..."]]
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
