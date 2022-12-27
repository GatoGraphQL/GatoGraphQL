<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;

class QueryableTagTaxonomiesModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    protected function getEndpoint(): string
    {
        return 'graphql/';
    }

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-queryable-tags';
    }

    protected function getSettingsKey(): string
    {
        return ModuleSettingOptions::TAG_TAXONOMIES;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'graphqlapi_graphqlapi_schema-tags';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        $value = [
            'post_format',
        ];

        $dataName = $this->getDataName();
        if (str_ends_with($dataName, ':1')) {
            $value[] = 'post_tag';
        } else {
            $value[] = 'dummy-tag';
        }

        return $value;
    }
}
