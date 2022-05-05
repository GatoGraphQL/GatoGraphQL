<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use PoP\AccessControl\Schema\SchemaModes;

class DefaultPublicPrivateSchemaModeModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected function getEndpoint(): string
    {
        // This endpoint has "Visibility of the schema metadata" as "Default"
        return 'graphql/power-users/';
    }

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-default-public-private-schema-mode';
    }

    protected function getSettingsKey(): string
    {
        return SchemaConfigurationFunctionalityModuleResolver::OPTION_MODE;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'graphqlapi_graphqlapi_public-private-schema';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return SchemaModes::PRIVATE_SCHEMA_MODE;
    }
}
