<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;

/**
 * This test is actually validating that all types/interfaces in the plugin
 * are the canonical, and so they have no namespacing. Then, both
 * "introspection-query.json" (namespaced schema) and
 * "introspection-query:0.json" (non-namespaced schema) are the same.
 */
class CanonicalNamespacingModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    /**
     * Custom endpoint "unrestricted-schema" has no ACLs (so we get all the
     * fields when doing introspection), and "Namespacing" as "default"
     */
    protected function getEndpoint(): string
    {
        return 'graphql/unrestricted-schema/';
    }

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-canonical-namespacing';
    }

    protected function getSettingsKey(): string
    {
        return ModuleSettingOptions::DEFAULT_VALUE;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'graphqlapi_graphqlapi_schema-namespacing';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return true;
    }
}
