<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

/**
 * This test is actually validating that all types/interfaces in the plugin
 * are the canonical, and so they have no namespacing. Then, both
 * "introspection-query.json" (namespaced schema) and
 * "introspection-query:0.json" (non-namespaced schema) are the same.
 */
class CanonicalNamespacingModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractNamespacingModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-canonical-namespacing';
    }

    /**
     * Canonical types must always be the same, then use
     * the contents of one response.
     *
     * @param array<string,mixed> $providerItems
     * @return array<string,mixed>
     */
    protected static function customizeProviderEndpointEntries(array $providerItems): array
    {
        $providerItems = parent::customizeProviderEndpointEntries($providerItems);
        $providerItems['introspection-types:0'][1] = $providerItems['introspection-types'][1];
        return $providerItems;
    }
}
