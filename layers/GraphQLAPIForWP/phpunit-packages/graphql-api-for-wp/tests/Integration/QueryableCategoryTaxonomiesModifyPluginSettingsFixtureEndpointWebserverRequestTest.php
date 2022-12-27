<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;

class QueryableCategoryTaxonomiesModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected function getEndpoint(): string
    {
        return 'graphql/';
    }

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-queryable-categories';
    }

    protected function getSettingsKey(): string
    {
        return ModuleSettingOptions::CATEGORY_TAXONOMIES;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'graphqlapi_graphqlapi_schema-categories';
    }



    protected function getPluginSettingsNewValue(): mixed
    {
        $dataName = $this->getDataName();
        if (str_ends_with($dataName, ':1')) {
            return [
                'category',
            ];
        }

        return [
            'dummy-category',
        ];
    }
}
