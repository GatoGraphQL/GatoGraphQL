<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;

class AllowedCustomPostMetaModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCaseCase
{
    use AllowedCustomPostMetaFixtureEndpointWebserverRequestTestTrait;

    protected function getEndpoint(): string
    {
        return 'graphql/';
    }

    protected function getSettingsKey(): string
    {
        return ModuleSettingOptions::ENTRIES;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'graphqlapi_graphqlapi_schema-custompost-meta';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return $this->getAllowedCustomPostMetaKeyEntriesNewValue();
    }
}
