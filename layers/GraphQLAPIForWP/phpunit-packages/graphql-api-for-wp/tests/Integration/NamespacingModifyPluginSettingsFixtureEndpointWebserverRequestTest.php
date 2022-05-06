<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;

class NamespacingModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
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
        return __DIR__ . '/fixture-namespacing';
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
