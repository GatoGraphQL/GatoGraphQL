<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolver;

abstract class AbstractTreatUserRoleAsPrivateDataModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-user-role-as-private-data';
    }

    protected function getSettingsKey(): string
    {
        return SchemaTypeModuleResolver::OPTION_TREAT_USER_ROLE_AS_ADMIN_DATA;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'graphqlapi_graphqlapi_schema-user-roles';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return false;
    }
}
