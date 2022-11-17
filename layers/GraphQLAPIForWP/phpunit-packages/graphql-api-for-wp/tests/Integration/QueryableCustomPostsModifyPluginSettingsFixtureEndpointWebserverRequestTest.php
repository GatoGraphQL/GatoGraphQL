<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;

class QueryableCustomPostsModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected function getEndpoint(): string
    {
        return 'graphql/';
    }

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-queryable-customposts';
    }

    protected function getSettingsKey(): string
    {
        return ModuleSettingOptions::ENTRIES;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'graphqlapi_graphqlapi_schema-customposts';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        $value = [
            'post',
            'attachment',
            'nav_menu_item',
            'custom_css',
            'revision',
        ];
        
        $dataName = $this->getDataName();
        if (str_ends_with($dataName, ':1')) {
            $value[] = 'page';
        }

        return $value;
    }
}
