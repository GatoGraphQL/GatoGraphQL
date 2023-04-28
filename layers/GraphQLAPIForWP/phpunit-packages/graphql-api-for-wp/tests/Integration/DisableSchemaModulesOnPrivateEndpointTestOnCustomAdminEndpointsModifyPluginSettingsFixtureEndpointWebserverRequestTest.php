<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\GraphQLAPITesting\Hooks\AddDummyCustomAdminEndpointHook;

class DisableSchemaModulesOnPrivateEndpointTestOnCustomAdminEndpointsModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractDisableSchemaModulesOnPrivateEndpointTestOnCustomAdminEndpointsModifyPluginSettingsFixtureEndpointWebserverRequestTest
{
    protected function getAdminEndpointGroup(): string
    {
        return AddDummyCustomAdminEndpointHook::ADMIN_ENDPOINT_GROUP;
    }

    protected function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-disable-schema-modules-on-private-endpoints-no-change';
    }
}
