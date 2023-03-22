<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;
use PHPUnitForGraphQLAPI\GraphQLAPI\Integration\AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase;

class SchemaPayloadTypesForMutationsModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected function getEndpoint(): string
    {
        return 'graphql/mobile-app/';
    }

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-schema-payload-types-for-mutations';
    }

    protected function getSettingsKey(): string
    {
        return ModuleSettingOptions::DEFAULT_VALUE;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'graphqlapi_graphqlapi_schema-payload-types-for-mutations';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return false;
    }
}
