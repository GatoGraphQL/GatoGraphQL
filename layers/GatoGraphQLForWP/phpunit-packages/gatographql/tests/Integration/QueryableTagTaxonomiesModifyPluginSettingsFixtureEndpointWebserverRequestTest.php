<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;

class QueryableTagTaxonomiesModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    use QueryableTagTaxonomiesFixtureEndpointWebserverRequestTestTrait;

    protected static function getEndpoint(): string
    {
        return 'graphql';
    }

    protected function getSettingsKey(): string
    {
        return ModuleSettingOptions::TAG_TAXONOMIES;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'gatographql_gatographql_schema-tags';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return $this->getIncludedTagTaxonomiesNewValue();
    }
}
