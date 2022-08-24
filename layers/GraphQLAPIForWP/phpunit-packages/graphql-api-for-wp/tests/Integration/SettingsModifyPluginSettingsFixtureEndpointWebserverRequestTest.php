<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;
use PoPSchema\SchemaCommons\Constants\Behaviors;

class SettingsModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected function getEndpoint(): string
    {
        return 'graphql/';
    }

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-settings';
    }

    protected function getSettingsKey(): string
    {
        return ModuleSettingOptions::BEHAVIOR;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'graphqlapi_graphqlapi_schema-settings';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return Behaviors::ALLOWLIST;
    }
}
