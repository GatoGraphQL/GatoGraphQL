<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

class DefaultSchemaConfigurationForPersistedQueryEndpointQueryExecutionModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractDefaultSchemaConfigurationForEndpointQueryExecutionModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    /**
     * Persisted Query endpoint
     */
    protected function getEndpoint(): string
    {
        return 'graphql-query/user-account/';
    }

    protected function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-default-schema-configuration-for-persisted-queries';
    }

    protected function getModuleID(string $dataName): string
    {
        return 'graphqlapi_graphqlapi_persisted-queries';
    }
}
