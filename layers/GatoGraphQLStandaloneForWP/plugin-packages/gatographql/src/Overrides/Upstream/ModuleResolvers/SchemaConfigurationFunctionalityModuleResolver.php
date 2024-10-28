<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Overrides\Upstream\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver as UpstreamSchemaConfigurationFunctionalityModuleResolver;
use GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes;

class SchemaConfigurationFunctionalityModuleResolver extends UpstreamSchemaConfigurationFunctionalityModuleResolver
{
    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return match ($module) {
            self::SCHEMA_CONFIGURATION,
            // self::SCHEMA_EXPOSE_SENSITIVE_DATA,
            self::RESPONSE_HEADERS,
            // self::MUTATIONS,
            // self::NESTED_MUTATIONS,
            self::SCHEMA_SELF_FIELDS,
            self::GLOBAL_ID_FIELD,
            self::SCHEMA_NAMESPACING
            // self::GLOBAL_FIELDS,
            // self::COMPOSABLE_DIRECTIVES,
            // self::MULTIFIELD_DIRECTIVES
                => false,
            default => parent::isPredefinedEnabledOrDisabled($module),
        };
    }

    public function isHidden(string $module): bool
    {
        return match ($module) {
            self::SCHEMA_CONFIGURATION,
            // self::SCHEMA_EXPOSE_SENSITIVE_DATA,
            self::RESPONSE_HEADERS,
            // self::MUTATIONS,
            // self::NESTED_MUTATIONS,
            self::SCHEMA_SELF_FIELDS,
            self::GLOBAL_ID_FIELD,
            self::SCHEMA_NAMESPACING
            // self::GLOBAL_FIELDS,
            // self::COMPOSABLE_DIRECTIVES,
            // self::MULTIFIELD_DIRECTIVES
                => true,
            default => parent::isHidden($module),
        };
    }

    public function getSettingsDefaultValue(string $module, string $option): mixed
    {
        $defaultValues = [
            self::NESTED_MUTATIONS => [
                ModuleSettingOptions::DEFAULT_VALUE => MutationSchemes::NESTED_WITH_REDUNDANT_ROOT_FIELDS,
            ],
            self::SCHEMA_EXPOSE_SENSITIVE_DATA => [
                ModuleSettingOptions::DEFAULT_VALUE => true,
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
                self::SCHEMA_EXPOSE_SENSITIVE_DATA,
                self::MUTATIONS,
                self::NESTED_MUTATIONS,
                self::GLOBAL_FIELDS,
                self::COMPOSABLE_DIRECTIVES,
                self::MULTIFIELD_DIRECTIVES,
            ])
        ) {
            return [];
        }

        return parent::getSettings($module);
    }
}
