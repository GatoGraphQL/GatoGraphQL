<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolver;

abstract class AbstractTreatUserEmailAsPrivateDataModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-user-email-as-private-data';
    }

    protected function getSettingsKey(): string
    {
        return SchemaTypeModuleResolver::OPTION_TREAT_USER_EMAIL_AS_ADMIN_DATA;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'graphqlapi_graphqlapi_schema-users';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return false;
    }
}
