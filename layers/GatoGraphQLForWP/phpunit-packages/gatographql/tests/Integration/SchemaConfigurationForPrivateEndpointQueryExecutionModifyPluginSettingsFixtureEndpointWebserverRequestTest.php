<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\GatoGraphQL\Integration\SchemaConfigurationForEndpointsDefaultQueryExecutionModifyPluginSettingsFixtureEndpointWebserverRequestTestTrait;

class SchemaConfigurationForPrivateEndpointQueryExecutionModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractDefaultSchemaConfigurationForEndpointQueryExecutionModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    use SchemaConfigurationForEndpointsDefaultQueryExecutionModifyPluginSettingsFixtureEndpointWebserverRequestTestTrait;

    /**
     * Admin client endpoint
     */
    protected static function getEndpoint(): string
    {
        return 'wp-admin/edit.php?page=gatographql&action=execute_query';
    }

    protected function getModuleID(string $dataName): string
    {
        return 'gatographql_gatographql_private-endpoint';
    }
}
