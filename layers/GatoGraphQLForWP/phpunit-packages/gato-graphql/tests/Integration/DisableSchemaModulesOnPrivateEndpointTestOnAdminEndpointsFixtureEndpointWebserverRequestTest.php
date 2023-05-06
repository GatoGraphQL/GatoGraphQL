<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class DisableSchemaModulesOnPrivateEndpointTestOnAdminEndpointsFixtureEndpointWebserverRequestTest extends AbstractDisableSchemaModulesOnPrivateEndpointsFixtureEndpointWebserverRequestTestCase
{
    protected function getEndpoint(): string
    {
        return 'wp-admin/edit.php?page=gato_graphql&action=execute_query';
    }

    protected function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-disable-schema-modules-on-private-endpoints-has-change';
    }
}
