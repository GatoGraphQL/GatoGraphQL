<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Overrides\Upstream\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\MetaSchemaTypeModuleResolver as UpstreamMetaSchemaTypeModuleResolver;

class MetaSchemaTypeModuleResolver extends UpstreamMetaSchemaTypeModuleResolver
{
    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return match ($module) {
            self::SCHEMA_CUSTOMPOST_META,
            self::SCHEMA_USER_META,
            self::SCHEMA_COMMENT_META,
            self::SCHEMA_TAXONOMY_META
                => false,
            default => parent::isPredefinedEnabledOrDisabled($module),
        };
    }

    public function isHidden(string $module): bool
    {
        return match ($module) {
            self::SCHEMA_CUSTOMPOST_META,
            self::SCHEMA_USER_META,
            self::SCHEMA_COMMENT_META,
            self::SCHEMA_TAXONOMY_META
                => true,
            default => parent::isHidden($module),
        };
    }
}
