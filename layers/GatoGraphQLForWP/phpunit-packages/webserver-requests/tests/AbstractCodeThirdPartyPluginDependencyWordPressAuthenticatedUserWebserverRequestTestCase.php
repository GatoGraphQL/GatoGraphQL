<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

use PoP\Root\Exception\ShouldNotHappenException;

/**
 * Test that enabling/disabling a required 3rd-party plugin works well.
 */
abstract class AbstractCodeThirdPartyPluginDependencyWordPressAuthenticatedUserWebserverRequestTestCase extends AbstractThirdPartyPluginDependencyWordPressAuthenticatedUserWebserverRequestTestCase
{
    /**
     * @return array<string,array<string,mixed>> An array of [$pluginName => ['query' => "...", 'response-enabled' => "...", 'response-disabled' => "..."]]
     */
    protected static function getPluginNameEntries(): array
    {
        $pluginEntries = [];
        foreach (static::getPluginNames() as $pluginName) {
            $pluginEntries[$pluginName] = [
                'query' => static::getPluginGraphQLQuery($pluginName),
                'response-enabled' => static::getPluginEnabledExpectedGraphQLResponse($pluginName),
                'response-disabled' => static::getPluginDisabledExpectedGraphQLResponse($pluginName),
            ];
        }
        return $pluginEntries;
    }

    /**
     * @return string[]
     */
    abstract protected static function getPluginNames(): array;

    protected static function getPluginGraphQLQuery(string $pluginName): string
    {
        static::throwUnsupportedPluginName($pluginName);
    }

    protected static function getPluginEnabledExpectedGraphQLResponse(string $pluginName): string
    {
        static::throwUnsupportedPluginName($pluginName);
    }

    protected static function getPluginDisabledExpectedGraphQLResponse(string $pluginName): string
    {
        static::throwUnsupportedPluginName($pluginName);
    }

    protected static function throwUnsupportedPluginName(string $pluginName): never
    {
        throw new ShouldNotHappenException(
            sprintf(
                'Configuration for plugin "%s" is not complete',
                $pluginName
            )
        );
    }
}
