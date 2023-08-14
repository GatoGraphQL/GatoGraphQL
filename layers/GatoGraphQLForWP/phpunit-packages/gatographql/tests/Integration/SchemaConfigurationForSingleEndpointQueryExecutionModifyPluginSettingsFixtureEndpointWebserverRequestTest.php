<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class SchemaConfigurationForSingleEndpointQueryExecutionModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractDefaultSchemaConfigurationForEndpointQueryExecutionModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    /**
     * Single endpoint
     */
    protected static function getEndpoint(): string
    {
        return 'graphql';
    }

    protected static function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-default-schema-configuration-for-endpoints';
    }

    protected function getModuleID(string $dataName): string
    {
        return 'gatographql_gatographql_single-endpoint';
    }
}
