<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

class DisableSchemaModulesOnAdminPersistedQueryEndpointTestOnCustomAdminEndpointsModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractDisableSchemaModulesOnAdminPersistedQueryEndpointTestOnCustomAdminEndpointsModifyPluginSettingsFixtureEndpointWebserverRequestTest
{
    private const USER_ACCOUNT_PERSISTED_QUERY_ID = 237;

    protected function getPersistedQueryID(): int
    {
        return self::USER_ACCOUNT_PERSISTED_QUERY_ID;
    }

    protected function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-disable-schema-modules-on-private-endpoints-single-endpoint';
    }
}
