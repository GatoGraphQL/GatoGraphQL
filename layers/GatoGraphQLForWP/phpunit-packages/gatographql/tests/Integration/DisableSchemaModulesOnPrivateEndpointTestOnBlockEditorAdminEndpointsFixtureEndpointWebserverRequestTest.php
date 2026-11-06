<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Enums\AdminGraphQLEndpointGroups;

class DisableSchemaModulesOnPrivateEndpointTestOnBlockEditorAdminEndpointsFixtureEndpointWebserverRequestTest extends AbstractDisableSchemaModulesOnPrivateEndpointTestOnCustomAdminEndpointsFixtureEndpointWebserverRequestTestCase
{
    use DisableSchemaModulesOnPrivateEndpointNoChangeAdminEndpointsFixtureEndpointWebserverRequestTestTrait;

    protected static function getAdminEndpointGroup(): string
    {
        return AdminGraphQLEndpointGroups::BLOCK_EDITOR->value;
    }
}
