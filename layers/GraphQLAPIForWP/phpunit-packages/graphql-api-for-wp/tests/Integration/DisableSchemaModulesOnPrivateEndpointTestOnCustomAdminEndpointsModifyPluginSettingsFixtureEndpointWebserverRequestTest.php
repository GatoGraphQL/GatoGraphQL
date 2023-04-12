<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\GraphQLAPITesting\Hooks\AddDummyCustomAdminEndpointHook;

class DisableSchemaModulesOnPrivateEndpointTestOnCustomAdminEndpointsModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractDisableSchemaModulesOnPrivateEndpointsModifyPluginSettingsFixtureEndpointWebserverRequestTest
{
    protected function getEndpoint(): string
    {
        return sprintf(
            'wp-admin/edit.php?page=graphql_api&action=execute_query&endpoint_group=%s',
            AddDummyCustomAdminEndpointHook::ADMIN_ENDPOINT_GROUP
        );
    }

    protected function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-disable-schema-modules-on-private-endpoints-custom-admin-endpoint';
    }
}
