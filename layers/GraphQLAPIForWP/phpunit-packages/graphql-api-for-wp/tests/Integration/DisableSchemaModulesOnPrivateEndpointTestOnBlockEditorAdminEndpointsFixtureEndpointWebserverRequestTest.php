<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Constants\AdminGraphQLEndpointGroups;

class DisableSchemaModulesOnPrivateEndpointTestOnBlockEditorAdminEndpointsFixtureEndpointWebserverRequestTest extends AbstractDisableSchemaModulesOnPrivateEndpointTestOnCustomAdminEndpointsFixtureEndpointWebserverRequestTestCase
{
    use DisableSchemaModulesOnPrivateEndpointNoChangeAdminEndpointsFixtureEndpointWebserverRequestTestTrait;

    protected function getAdminEndpointGroup(): string
    {
        return AdminGraphQLEndpointGroups::BLOCK_EDITOR;
    }
}
