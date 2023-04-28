<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\GraphQLAPITesting\Hooks\AddDummyCustomAdminEndpointHook;

class DisableSchemaModulesOnPrivateEndpointTestOnCustomAdminEndpointsModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractDisableSchemaModulesOnPrivateEndpointTestOnCustomAdminEndpointsModifyPluginSettingsFixtureEndpointWebserverRequestTest
{
    use DisableSchemaModulesOnPrivateEndpointNoChangeAdminEndpointsModifyPluginSettingsFixtureEndpointWebserverRequestTestTrait;

    protected function getAdminEndpointGroup(): string
    {
        return AddDummyCustomAdminEndpointHook::ADMIN_ENDPOINT_GROUP;
    }
}
