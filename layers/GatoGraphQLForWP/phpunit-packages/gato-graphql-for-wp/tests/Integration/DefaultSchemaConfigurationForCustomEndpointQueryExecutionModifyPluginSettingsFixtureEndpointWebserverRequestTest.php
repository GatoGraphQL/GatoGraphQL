<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class DefaultSchemaConfigurationForCustomEndpointQueryExecutionModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractDefaultSchemaConfigurationForEndpointQueryExecutionModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    /**
     * Custom endpoint
     */
    protected function getEndpoint(): string
    {
        return 'graphql/back-end-for-dev/';
    }

    protected function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-default-schema-configuration-for-endpoints';
    }

    protected function getModuleID(string $dataName): string
    {
        return 'graphqlapi_graphqlapi_custom-endpoints';
    }
}
