<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class DisableSchemaModulesOnAdminPersistedQueryEndpointTestOnCustomAdminEndpointsFixtureEndpointWebserverRequestTest extends AbstractDisableSchemaModulesOnAdminPersistedQueryEndpointTestOnCustomAdminEndpointsFixtureEndpointWebserverRequestTestCase
{
    use DisableSchemaModulesOnPrivateEndpointHasChangeFixtureEndpointWebserverRequestTestTrait;

    private const USER_ACCOUNT_PERSISTED_QUERY_ID = 237;

    protected static function getPersistedQueryID(): int
    {
        return self::USER_ACCOUNT_PERSISTED_QUERY_ID;
    }
}
