<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Overrides\Upstream\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\MetaSchemaTypeModuleResolver as UpstreamMetaSchemaTypeModuleResolver;

class MetaSchemaTypeModuleResolver extends UpstreamMetaSchemaTypeModuleResolver
{
    use OverrideToDisableModuleResolverTrait;

    public function getSettingsDefaultValue(string $module, string $option): mixed
    {
        $defaultValues = [
            self::SCHEMA_CUSTOMPOST_META => [
                self::OPTION_TREAT_META_KEYS_AS_SENSITIVE_DATA => false,
            ],
            self::SCHEMA_USER_META => [
                self::OPTION_TREAT_META_KEYS_AS_SENSITIVE_DATA => false,
            ],
            self::SCHEMA_COMMENT_META => [
                self::OPTION_TREAT_META_KEYS_AS_SENSITIVE_DATA => false,
            ],
            self::SCHEMA_TAXONOMY_META => [
                self::OPTION_TREAT_META_KEYS_AS_SENSITIVE_DATA => false,
            ],
        ];
        return $defaultValues[$module][$option] ?? parent::getSettingsDefaultValue($module, $option);
    }
}
