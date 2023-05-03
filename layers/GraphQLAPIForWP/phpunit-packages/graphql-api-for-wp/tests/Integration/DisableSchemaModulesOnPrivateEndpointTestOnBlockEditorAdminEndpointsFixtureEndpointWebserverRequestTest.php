<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Constants\AdminGraphQLEndpointGroups;

class DisableSchemaModulesOnPrivateEndpointTestOnBlockEditorAdminEndpointsFixtureEndpointWebserverRequestTest extends AbstractDisableSchemaModulesOnPrivateEndpointTestOnCustomAdminEndpointsFixtureEndpointWebserverRequestTestCase
{
    use DisableSchemaModulesOnPrivateEndpointNoChangeAdminEndpointsFixtureEndpointWebserverRequestTestTrait;

    protected function getAdminEndpointGroup(): string
    {
        return AdminGraphQLEndpointGroups::BLOCK_EDITOR;
    }
}
