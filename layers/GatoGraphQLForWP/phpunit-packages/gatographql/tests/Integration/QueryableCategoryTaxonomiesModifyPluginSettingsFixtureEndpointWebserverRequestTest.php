<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;

class QueryableCategoryTaxonomiesModifyPluginSettingsFixtureEndpointWebserverRequestTest extends AbstractModifyPluginSettingsFixtureEndpointWebserverRequestTestCase
{
    use QueryableCategoryTaxonomiesFixtureEndpointWebserverRequestTestTrait;

    protected static function getEndpoint(): string
    {
        return 'graphql';
    }

    protected function getSettingsKey(): string
    {
        return ModuleSettingOptions::CATEGORY_TAXONOMIES;
    }

    protected function getModuleID(string $dataName): string
    {
        return 'gatographql_gatographql_schema-categories';
    }

    protected function getPluginSettingsNewValue(): mixed
    {
        return $this->getIncludedCategoryTaxonomiesNewValue();
    }
}
