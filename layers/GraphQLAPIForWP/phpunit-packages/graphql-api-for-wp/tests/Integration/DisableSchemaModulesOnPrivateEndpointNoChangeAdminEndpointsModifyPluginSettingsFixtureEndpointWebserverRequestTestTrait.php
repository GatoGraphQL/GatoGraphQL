<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

trait DisableSchemaModulesOnPrivateEndpointNoChangeAdminEndpointsModifyPluginSettingsFixtureEndpointWebserverRequestTestTrait
{
    protected function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-disable-schema-modules-on-private-endpoints-no-change';
    }
}
