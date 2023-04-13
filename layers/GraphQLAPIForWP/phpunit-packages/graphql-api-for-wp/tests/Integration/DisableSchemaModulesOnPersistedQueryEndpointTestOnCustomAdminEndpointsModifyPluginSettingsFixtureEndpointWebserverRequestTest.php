<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

class DisableSchemaModulesOnPersistedQueryEndpointTestOnCustomAdminEndpointsModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractDisableSchemaModulesOnPrivateEndpointsModifyPluginSettingsFixtureEndpointWebserverRequestTest
{
    protected function getEndpoint(): string
    {
        return 'graphql-query/user-account/';
    }

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-disable-schema-modules-on-private-endpoints-persisted-query';
    }
}
