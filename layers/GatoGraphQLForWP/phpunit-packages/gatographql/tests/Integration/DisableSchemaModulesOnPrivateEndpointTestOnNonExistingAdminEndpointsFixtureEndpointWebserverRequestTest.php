<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class DisableSchemaModulesOnPrivateEndpointTestOnNonExistingAdminEndpointsFixtureEndpointWebserverRequestTest extends AbstractDisableSchemaModulesOnPrivateEndpointTestOnCustomAdminEndpointsFixtureEndpointWebserverRequestTestCase
{
    use DisableSchemaModulesOnPrivateEndpointHasChangeFixtureEndpointWebserverRequestTestTrait;

    /**
     * Because it doesn't exist, the response will be treated
     * as the default one.
     */
    protected static function getAdminEndpointGroup(): string
    {
        return 'nonExistingGroup';
    }
}
