<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Overrides\Upstream\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaTypeModuleResolver as UpstreamSchemaTypeModuleResolver;

class SchemaTypeModuleResolver extends UpstreamSchemaTypeModuleResolver
{
    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return match ($module) {
            // self::SCHEMA_CUSTOMPOSTS,
            self::SCHEMA_POSTS,
            // self::SCHEMA_BLOCKS,
            self::SCHEMA_PAGES,
            // self::SCHEMA_USERS,
            self::SCHEMA_USER_ROLES,
            self::SCHEMA_USER_AVATARS,
            self::SCHEMA_COMMENTS,
            self::SCHEMA_SITE,
            self::SCHEMA_MULTISITE,
            // self::SCHEMA_TAGS,
            self::SCHEMA_POST_TAGS,
            // self::SCHEMA_CATEGORIES,
            self::SCHEMA_POST_CATEGORIES,
            // self::SCHEMA_MEDIA
            self::SCHEMA_MENUS
            // self::SCHEMA_SETTINGS
                => false,
            default => parent::isPredefinedEnabledOrDisabled($module),
        };
    }

    public function isHidden(string $module): bool
    {
        return match ($module) {
            // self::SCHEMA_CUSTOMPOSTS,
            self::SCHEMA_POSTS,
            // self::SCHEMA_BLOCKS,
            self::SCHEMA_PAGES,
            // self::SCHEMA_USERS,
            self::SCHEMA_USER_ROLES,
            self::SCHEMA_USER_AVATARS,
            self::SCHEMA_COMMENTS,
            self::SCHEMA_SITE,
            self::SCHEMA_MULTISITE,
            // self::SCHEMA_TAGS,
            self::SCHEMA_POST_TAGS,
            // self::SCHEMA_CATEGORIES,
            self::SCHEMA_POST_CATEGORIES,
            // self::SCHEMA_MEDIA
            self::SCHEMA_MENUS
            // self::SCHEMA_SETTINGS
                => true,
            default => parent::isHidden($module),
        };
    }

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
            self::SCHEMA_USERS => [
                ModuleSettingOptions::LIST_MAX_LIMIT => -1,
            ],
            self::SCHEMA_MEDIA => [
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

    /**
     * @return array<array<string,mixed>> List of settings for the module, each entry is an array with property => value
     */
    public function getSettings(string $module): array
    {
        if (
            in_array($module, [
                self::SCHEMA_CUSTOMPOSTS,
                self::SCHEMA_BLOCKS,
                self::SCHEMA_USERS,
                self::SCHEMA_TAGS,
                self::SCHEMA_CATEGORIES,
                self::SCHEMA_MEDIA,
                self::SCHEMA_SETTINGS
            ])
        ) {
            return [];
        }

        return parent::getSettings($module);
    }
}
