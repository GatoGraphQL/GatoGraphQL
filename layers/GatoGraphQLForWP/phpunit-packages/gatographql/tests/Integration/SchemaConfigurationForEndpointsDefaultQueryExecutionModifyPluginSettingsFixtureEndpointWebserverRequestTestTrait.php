<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

trait SchemaConfigurationForEndpointsDefaultQueryExecutionModifyPluginSettingsFixtureEndpointWebserverRequestTestTrait
{
    protected static function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-default-schema-configuration-for-endpoints';
    }
}
