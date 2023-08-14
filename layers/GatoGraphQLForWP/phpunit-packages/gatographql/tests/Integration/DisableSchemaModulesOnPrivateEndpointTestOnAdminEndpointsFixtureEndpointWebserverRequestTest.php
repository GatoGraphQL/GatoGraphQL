<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class DisableSchemaModulesOnPrivateEndpointTestOnAdminEndpointsFixtureEndpointWebserverRequestTest extends AbstractDisableSchemaModulesOnPrivateEndpointsFixtureEndpointWebserverRequestTestCase
{
    protected static function getEndpoint(): string
    {
        return 'wp-admin/edit.php?page=gatographql&action=execute_query';
    }

    protected static function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-disable-schema-modules-on-private-endpoints-has-change';
    }
}
