<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class SchemaConfigurationForPrivateEndpointQueryExecutionModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractDefaultSchemaConfigurationForEndpointQueryExecutionModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    /**
     * Admin client endpoint
     */
    protected function getEndpoint(): string
    {
        return 'wp-admin/edit.php?page=gato_graphql&action=execute_query';
    }

    protected function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-default-schema-configuration-for-endpoints';
    }

    protected function getModuleID(string $dataName): string
    {
        return 'gatographql_gatographql_private-endpoint';
    }
}
