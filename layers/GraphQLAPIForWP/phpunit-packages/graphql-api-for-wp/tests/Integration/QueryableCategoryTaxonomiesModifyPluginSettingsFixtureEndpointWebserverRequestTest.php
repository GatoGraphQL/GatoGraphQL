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
        $value = [
            // The DB data does not have another category for testing, so use a fake value
            'pretend_this_category_exists',
        ];

        $dataName = $this->getDataName();
        if (str_ends_with($dataName, ':1')) {
            $value[] = 'category';
        }

        return $value;
    }
}
