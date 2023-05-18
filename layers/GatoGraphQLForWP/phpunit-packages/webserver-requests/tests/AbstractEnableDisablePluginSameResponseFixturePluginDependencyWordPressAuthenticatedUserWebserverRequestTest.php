<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

/**
 * Test that enabling/disabling a plugin does not affect
 * the response of the query.
 */
abstract class AbstractEnableDisablePluginSameResponseFixturePluginDependencyWordPressAuthenticatedUserWebserverRequestTest extends AbstractFixtureThirdPartyPluginDependencyWordPressAuthenticatedUserWebserverRequestTestCase
{
    /**
     * The response before/after must be the same.
     * 
     * For that, have the ":disabled.json" file copy
     * the contents of ":enabled.json".
     *
     * @param array<string,array<string,mixed>> $pluginEntries
     * @return array<string,array<string,mixed>>
     */
    protected function customizePluginNameEntries(array $pluginEntries): array
    {
        $pluginEntries = parent::customizePluginNameEntries($pluginEntries);
        foreach ($pluginEntries as $pluginName => &$pluginEntry) {
            $pluginEntry['response-disabled'] = $pluginEntry['response-enabled'];
        }
        return $pluginEntries;
    }
}
