<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;

class UserMetaModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected function getEndpoint(): string
    {
        return 'graphql/';
    }

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-user-meta';
    }

    protected function getSettingsKey(): string
    {
        return ModuleSettingOptions::ENTRIES;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'graphqlapi_graphqlapi_schema-user-meta';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        $dataName = $this->dataName();
        if (str_ends_with($dataName, ':1')) {
            return [
                '/.*name/',
            ];
        }
        return [
            'nickname',
            'first_name',
        ];
    }
}
