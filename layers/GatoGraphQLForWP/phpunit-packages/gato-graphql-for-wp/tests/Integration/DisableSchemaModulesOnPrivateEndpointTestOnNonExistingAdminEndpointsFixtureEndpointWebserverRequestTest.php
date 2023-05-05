<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class DisableSchemaModulesOnPrivateEndpointTestOnNonExistingAdminEndpointsFixtureEndpointWebserverRequestTest extends AbstractDisableSchemaModulesOnPrivateEndpointTestOnCustomAdminEndpointsFixtureEndpointWebserverRequestTestCase
{
    protected function getAdminEndpointGroup(): string
    {
        return 'nonExistingGroup';
    }

    /**
     * Because it doesn't exist, it will be treated
     * as the default one.
     */
    protected function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-disable-schema-modules-on-private-endpoints-has-change';
    }
}
