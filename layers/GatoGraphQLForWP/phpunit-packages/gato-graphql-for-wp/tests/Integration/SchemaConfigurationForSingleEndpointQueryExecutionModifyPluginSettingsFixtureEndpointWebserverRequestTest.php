<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class SchemaConfigurationForSingleEndpointQueryExecutionModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractDefaultSchemaConfigurationForEndpointQueryExecutionModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    /**
     * Single endpoint
     */
    protected function getEndpoint(): string
    {
        return 'graphql';
    }

    protected function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-default-schema-configuration-for-endpoints';
    }

    protected function getModuleID(string $dataName): string
    {
        return 'graphqlapi_graphqlapi_single-endpoint';
    }
}
