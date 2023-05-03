<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\GraphQLAPITesting\Hooks\AddDummyCustomAdminEndpointHook;

class DisableSchemaModulesOnPrivateEndpointTestOnCustomAdminEndpointsFixtureEndpointWebserverRequestTest extends AbstractDisableSchemaModulesOnPrivateEndpointTestOnCustomAdminEndpointsFixtureEndpointWebserverRequestTestCase
{
    use DisableSchemaModulesOnPrivateEndpointNoChangeAdminEndpointsFixtureEndpointWebserverRequestTestTrait;

    protected function getAdminEndpointGroup(): string
    {
        return AddDummyCustomAdminEndpointHook::ADMIN_ENDPOINT_GROUP;
    }
}
