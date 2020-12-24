<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ModuleResolverTrait;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleTypeResolvers\ModuleTypeResolver;

/**
 * The cache modules have different behavior depending on the environment:
 * - "development": visible, disabled by default
 * - "production": hidden, enabled by default
 *
 * @author Leonardo Losoviz <leo@getpop.org>
 */
class PerformanceFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;

    public const CACHE_CONTROL = Plugin::NAMESPACE . '\cache-control';

    /**
     * Setting options
     */
    public const OPTION_MAX_AGE = 'max-age';

    /**
     * @return string[]
     */
    public static function getModulesToResolve(): array
    {
        return [
            self::CACHE_CONTROL,
        ];
    }

    /**
     * Enable to customize a specific UI for the module
     */
    public function getModuleType(string $module): string
    {
        return ModuleTypeResolver::PERFORMANCE;
    }

    /**
     * @return array<array> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     */
    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::CACHE_CONTROL:
                return [
                    [
                        SchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION,
                    ],
                    [
                        EndpointFunctionalityModuleResolver::PERSISTED_QUERIES,
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        $names = [
            self::CACHE_CONTROL => \__('Cache Control', 'graphql-api'),
        ];
        return $names[$module] ?? $module;
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::CACHE_CONTROL:
                return \__('Provide HTTP Caching for Persisted Queries, sending the Cache-Control header with a max-age value calculated from all fields in the query', 'graphql-api');
        }
        return parent::getDescription($module);
    }

    /**
     * Default value for an option set by the module
     *
     * @param string $module
     * @param string $option
     * @return mixed Anything the setting might be: an array|string|bool|int|null
     */
    public function getSettingsDefaultValue(string $module, string $option)
    {
        $defaultValues = [
            self::CACHE_CONTROL => [
                self::OPTION_MAX_AGE => 86400, // 1 day
            ],
        ];
        return $defaultValues[$module][$option];
    }

    /**
     * Array with the inputs to show as settings for the module
     *
     * @return array<array> List of settings for the module, each entry is an array with property => value
     */
    public function getSettings(string $module): array
    {
        $moduleSettings = parent::getSettings($module);
        // Do the if one by one, so that the SELECT do not get evaluated unless needed
        if ($module == self::CACHE_CONTROL) {
            $option = self::OPTION_MAX_AGE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Default max-age', 'graphql-api'),
                Properties::DESCRIPTION => \__('Default max-age value (in seconds) for the Cache-Control header, for all fields and directives in the schema', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_INT,
                Properties::MIN_NUMBER => 0,
            ];
        }
        return $moduleSettings;
    }
}
