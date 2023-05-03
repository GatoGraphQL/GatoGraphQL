<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;

class QueryableCustomPostsModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCaseCase
{
    use QueryableCustomPostsFixtureEndpointWebserverRequestTestTrait;

    protected function getEndpoint(): string
    {
        return 'graphql/';
    }

    protected function getSettingsKey(): string
    {
        return ModuleSettingOptions::CUSTOMPOST_TYPES;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'graphqlapi_graphqlapi_schema-customposts';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return $this->getIncludedCustomPostTypesNewValue();
    }
}
