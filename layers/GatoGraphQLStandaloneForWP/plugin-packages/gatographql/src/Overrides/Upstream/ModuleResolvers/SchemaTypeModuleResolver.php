<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Overrides\Upstream\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaTypeModuleResolver as UpstreamSchemaTypeModuleResolver;

class SchemaTypeModuleResolver extends UpstreamSchemaTypeModuleResolver
{
    use OverrideToDisableModuleResolverTrait;

    public function getSettingsDefaultValue(string $module, string $option): mixed
    {
        if (
            $module === self::SCHEMA_CUSTOMPOSTS
            && $option === ModuleSettingOptions::CUSTOMPOST_TYPES
        ) {
            return $this->getWPDataModelProvider()->getFilteredNonGatoGraphQLPluginCustomPostTypes();
        }

        if (
            $module === self::SCHEMA_TAGS
            && $option === ModuleSettingOptions::TAG_TAXONOMIES
        ) {
            return $this->getWPDataModelProvider()->getFilteredNonGatoGraphQLPluginTagTaxonomies();
        }

        if (
            $module === self::SCHEMA_CATEGORIES
            && $option === ModuleSettingOptions::CATEGORY_TAXONOMIES
        ) {
            return $this->getWPDataModelProvider()->getFilteredNonGatoGraphQLPluginCategoryTaxonomies();
        }

        $defaultValues = [
            self::SCHEMA_CUSTOMPOSTS => [
                ModuleSettingOptions::LIST_MAX_LIMIT => -1,
            ],
            self::SCHEMA_POSTS => [
                ModuleSettingOptions::LIST_MAX_LIMIT => -1,
            ],
            self::SCHEMA_PAGES => [
                ModuleSettingOptions::LIST_MAX_LIMIT => -1,
            ],
            self::SCHEMA_USERS => [
                ModuleSettingOptions::LIST_MAX_LIMIT => -1,
            ],
            self::SCHEMA_MEDIA => [
                ModuleSettingOptions::LIST_MAX_LIMIT => -1,
            ],
            self::SCHEMA_MENUS => [
                ModuleSettingOptions::LIST_MAX_LIMIT => -1,
            ],
            self::SCHEMA_TAGS => [
                ModuleSettingOptions::LIST_MAX_LIMIT => -1,
            ],
            self::SCHEMA_CATEGORIES => [
                ModuleSettingOptions::LIST_MAX_LIMIT => -1,
            ],
        ];
        return $defaultValues[$module][$option] ?? parent::getSettingsDefaultValue($module, $option);
    }
}
