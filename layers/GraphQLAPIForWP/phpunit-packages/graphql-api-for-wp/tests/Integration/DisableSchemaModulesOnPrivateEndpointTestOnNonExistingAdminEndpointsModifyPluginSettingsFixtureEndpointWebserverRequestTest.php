<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

class DisableSchemaModulesOnPrivateEndpointTestOnNonExistingAdminEndpointsModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractDisableSchemaModulesOnPrivateEndpointTestOnCustomAdminEndpointsModifyPluginSettingsFixtureEndpointWebserverRequestTest
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
        return __DIR__ . '/fixture-disable-schema-modules-on-private-endpoints-admin-endpoint';
    }
}
