<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;

class QueryableTagTaxonomiesModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCaseCase
{
    use QueryableTagTaxonomiesFixtureEndpointWebserverRequestTestTrait;

    protected function getEndpoint(): string
    {
        return 'graphql/';
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
        return $this->getIncludedTagTaxonomiesNewValue();
    }
}
