<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

class SchemaConfigurationForPrivateEndpointQueryExecutionModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractDefaultSchemaConfigurationForEndpointQueryExecutionModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    /**
     * Admin client endpoint
     */
    protected function getEndpoint(): string
    {
        return 'wp-admin/edit.php?page=graphql_api&action=execute_query';
    }

    protected function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-default-schema-configuration-for-endpoints';
    }

    protected function getModuleID(string $dataName): string
    {
        return 'graphqlapi_graphqlapi_private-endpoint';
    }
}
