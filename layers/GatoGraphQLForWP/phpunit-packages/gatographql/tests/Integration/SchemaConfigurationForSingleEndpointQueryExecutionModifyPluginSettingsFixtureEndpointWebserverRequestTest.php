<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\GatoGraphQL\Integration\SchemaConfigurationForEndpointsDefaultQueryExecutionModifyPluginSettingsFixtureEndpointWebserverRequestTestTrait;

class SchemaConfigurationForSingleEndpointQueryExecutionModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractDefaultSchemaConfigurationForEndpointQueryExecutionModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    use SchemaConfigurationForEndpointsDefaultQueryExecutionModifyPluginSettingsFixtureEndpointWebserverRequestTestTrait;
    
    /**
     * Single endpoint
     */
    protected static function getEndpoint(): string
    {
        return 'graphql';
    }

    protected function getModuleID(string $dataName): string
    {
        return 'gatographql_gatographql_single-endpoint';
    }
}
