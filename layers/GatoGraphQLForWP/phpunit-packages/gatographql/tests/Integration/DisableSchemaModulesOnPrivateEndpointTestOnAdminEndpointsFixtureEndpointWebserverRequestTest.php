<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class DisableSchemaModulesOnPrivateEndpointTestOnAdminEndpointsFixtureEndpointWebserverRequestTest extends AbstractDisableSchemaModulesOnPrivateEndpointsFixtureEndpointWebserverRequestTestCase
{
    use DisableSchemaModulesOnPrivateEndpointHasChangeFixtureEndpointWebserverRequestTestTrait;
    
    protected static function getEndpoint(): string
    {
        return 'wp-admin/edit.php?page=gatographql&action=execute_query';
    }
}
