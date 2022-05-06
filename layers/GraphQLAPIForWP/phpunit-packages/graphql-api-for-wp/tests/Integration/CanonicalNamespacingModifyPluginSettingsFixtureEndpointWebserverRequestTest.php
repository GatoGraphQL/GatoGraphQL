<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

/**
 * This test is actually validating that all types/interfaces in the plugin
 * are the canonical, and so they have no namespacing. Then, both
 * "introspection-query.json" (namespaced schema) and
 * "introspection-query:0.json" (non-namespaced schema) are the same.
 */
class CanonicalNamespacingModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractNamespacingModifyPluginSettingsFixtureEndpointWebserverRequestTest
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-canonical-namespacing';
    }
}
