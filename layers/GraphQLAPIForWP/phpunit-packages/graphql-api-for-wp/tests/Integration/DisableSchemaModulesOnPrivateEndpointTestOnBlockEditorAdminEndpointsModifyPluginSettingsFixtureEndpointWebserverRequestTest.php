<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Constants\AdminGraphQLEndpointGroups;

class DisableSchemaModulesOnPrivateEndpointTestOnBlockEditorAdminEndpointsModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractDisableSchemaModulesOnPrivateEndpointTestOnCustomAdminEndpointsModifyPluginSettingsFixtureEndpointWebserverRequestTest
{
    use DisableSchemaModulesOnPrivateEndpointNoChangeAdminEndpointsModifyPluginSettingsFixtureEndpointWebserverRequestTestTrait;

    protected function getAdminEndpointGroup(): string
    {
        return AdminGraphQLEndpointGroups::BLOCK_EDITOR;
    }
}
