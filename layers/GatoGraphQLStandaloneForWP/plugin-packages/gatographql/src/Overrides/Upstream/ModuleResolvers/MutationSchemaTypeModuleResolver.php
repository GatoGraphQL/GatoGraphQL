<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Overrides\Upstream\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\MutationSchemaTypeModuleResolver as UpstreamMutationSchemaTypeModuleResolver;

class MutationSchemaTypeModuleResolver extends UpstreamMutationSchemaTypeModuleResolver
{
    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return match ($module) {
            // self::SCHEMA_USER_STATE_MUTATIONS,
            // self::SCHEMA_CUSTOMPOST_MUTATIONS,
            self::SCHEMA_PAGE_MUTATIONS,
            self::SCHEMA_POST_MUTATIONS,
            // self::SCHEMA_MEDIA_MUTATIONS,
            // self::SCHEMA_CUSTOMPOSTMEDIA_MUTATIONS,
            self::SCHEMA_PAGEMEDIA_MUTATIONS,
            self::SCHEMA_POSTMEDIA_MUTATIONS,
            // self::SCHEMA_CUSTOMPOST_USER_MUTATIONS,
            // self::SCHEMA_TAG_MUTATIONS,
            // self::SCHEMA_CUSTOMPOST_TAG_MUTATIONS,
            self::SCHEMA_POST_TAG_MUTATIONS,
            // self::SCHEMA_CATEGORY_MUTATIONS,
            // self::SCHEMA_CUSTOMPOST_CATEGORY_MUTATIONS
            self::SCHEMA_POST_CATEGORY_MUTATIONS,
            self::SCHEMA_COMMENT_MUTATIONS
                => false,
            default => parent::isPredefinedEnabledOrDisabled($module),
        };
    }

    public function isHidden(string $module): bool
    {
        return match ($module) {
            // self::SCHEMA_USER_STATE_MUTATIONS,
            // self::SCHEMA_CUSTOMPOST_MUTATIONS,
            self::SCHEMA_PAGE_MUTATIONS,
            self::SCHEMA_POST_MUTATIONS,
            // self::SCHEMA_MEDIA_MUTATIONS,
            // self::SCHEMA_CUSTOMPOSTMEDIA_MUTATIONS,
            self::SCHEMA_PAGEMEDIA_MUTATIONS,
            self::SCHEMA_POSTMEDIA_MUTATIONS,
            // self::SCHEMA_CUSTOMPOST_USER_MUTATIONS,
            // self::SCHEMA_TAG_MUTATIONS,
            // self::SCHEMA_CUSTOMPOST_TAG_MUTATIONS,
            self::SCHEMA_POST_TAG_MUTATIONS,
            // self::SCHEMA_CATEGORY_MUTATIONS,
            // self::SCHEMA_CUSTOMPOST_CATEGORY_MUTATIONS
            self::SCHEMA_POST_CATEGORY_MUTATIONS,
            self::SCHEMA_COMMENT_MUTATIONS
                => true,
            default => parent::isHidden($module),
        };
    }

    /**
     * @return array<array<string,mixed>> List of settings for the module, each entry is an array with property => value
     */
    public function getSettings(string $module): array
    {
        if (
            in_array($module, [
                self::SCHEMA_USER_STATE_MUTATIONS,
                self::SCHEMA_CUSTOMPOST_MUTATIONS,
                self::SCHEMA_MEDIA_MUTATIONS,
                self::SCHEMA_CUSTOMPOSTMEDIA_MUTATIONS,
                self::SCHEMA_CUSTOMPOST_USER_MUTATIONS,
                self::SCHEMA_TAG_MUTATIONS,
                self::SCHEMA_CUSTOMPOST_TAG_MUTATIONS,
                self::SCHEMA_CATEGORY_MUTATIONS,
                self::SCHEMA_CUSTOMPOST_CATEGORY_MUTATIONS,
            ])
        ) {
            return [];
        }

        return parent::getSettings($module);
    }
}
