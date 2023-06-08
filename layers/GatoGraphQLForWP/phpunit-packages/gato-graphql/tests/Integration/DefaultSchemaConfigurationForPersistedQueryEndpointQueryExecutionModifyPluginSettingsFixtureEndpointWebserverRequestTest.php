<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class DefaultSchemaConfigurationForPersistedQueryEndpointQueryExecutionModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractDefaultSchemaConfigurationForEndpointQueryExecutionModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    /**
     * Persisted Query endpoint
     */
    protected static function getEndpoint(): string
    {
        return 'graphql-query/user-account/';
    }

    protected static function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-default-schema-configuration-for-persisted-queries';
    }

    protected function getModuleID(string $dataName): string
    {
        return 'gatographql_gatographql_persisted-queries';
    }
}
