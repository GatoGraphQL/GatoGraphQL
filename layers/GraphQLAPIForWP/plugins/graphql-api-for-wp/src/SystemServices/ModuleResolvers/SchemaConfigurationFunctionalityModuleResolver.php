<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\SystemServices\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\Facades\Registries\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\SystemServices\ModuleResolvers\ModuleResolverTrait;
use GraphQLAPI\GraphQLAPI\PostTypes\GraphQLSchemaConfigurationPostType;
use PoP\AccessControl\Schema\SchemaModes;
use GraphQLAPI\GraphQLAPI\ComponentConfiguration;
use GraphQLAPI\GraphQLAPI\Services\ModuleTypeResolvers\ModuleTypeResolver;
use WP_Post;

class SchemaConfigurationFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;

    public const SCHEMA_CONFIGURATION = Plugin::NAMESPACE . '\schema-configuration';
    public const SCHEMA_NAMESPACING = Plugin::NAMESPACE . '\schema-namespacing';
    public const PUBLIC_PRIVATE_SCHEMA = Plugin::NAMESPACE . '\public-private-schema';

    /**
     * Setting options
     */
    public const OPTION_SCHEMA_CONFIGURATION_ID = 'schema-configuration-id';
    public const OPTION_USE_NAMESPACING = 'use-namespacing';
    public const OPTION_MODE = 'mode';
    public const OPTION_ENABLE_GRANULAR = 'granular';

    /**
     * Setting option values
     */
    public const OPTION_VALUE_NO_VALUE_ID = 0;

    /**
     * @return string[]
     */
    public static function getModulesToResolve(): array
    {
        return [
            self::SCHEMA_CONFIGURATION,
            self::SCHEMA_NAMESPACING,
            self::PUBLIC_PRIVATE_SCHEMA,
        ];
    }

    /**
     * Enable to customize a specific UI for the module
     */
    public function getModuleType(string $module): string
    {
        return ModuleTypeResolver::SCHEMA_CONFIGURATION;
    }

    /**
     * @return array<array> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     */
    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::SCHEMA_CONFIGURATION:
                return [
                    [
                        EndpointFunctionalityModuleResolver::PERSISTED_QUERIES,
                        EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS,
                    ],
                ];
            case self::SCHEMA_NAMESPACING:
                return [
                    [
                        self::SCHEMA_CONFIGURATION,
                    ],
                ];
            case self::PUBLIC_PRIVATE_SCHEMA:
                return [
                    [
                        AccessControlFunctionalityModuleResolver::ACCESS_CONTROL,
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        $names = [
            self::SCHEMA_CONFIGURATION => \__('Schema Configuration', 'graphql-api'),
            self::SCHEMA_NAMESPACING => \__('Schema Namespacing', 'graphql-api'),
            self::PUBLIC_PRIVATE_SCHEMA => \__('Public/Private Schema', 'graphql-api'),
        ];
        return $names[$module] ?? $module;
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::SCHEMA_CONFIGURATION:
                return \__('Customize the schema accessible to different Custom Endpoints and Persisted Queries, by applying a custom configuration (involving namespacing, access control, cache control, and others) to the grand schema', 'graphql-api');
            case self::SCHEMA_NAMESPACING:
                return \__('Automatically namespace types and interfaces with a vendor/project name, to avoid naming collisions', 'graphql-api');
            case self::PUBLIC_PRIVATE_SCHEMA:
                return \__('Enable to communicate the existence of some field from the schema to certain users only (private mode) or to everyone (public mode). If disabled, fields are always available to everyone (public mode)', 'graphql-api');
        }
        return parent::getDescription($module);
    }

    public function isEnabledByDefault(string $module): bool
    {
        switch ($module) {
            case self::SCHEMA_NAMESPACING:
                return false;
        }
        return parent::isEnabledByDefault($module);
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
            self::SCHEMA_CONFIGURATION => [
                self::OPTION_SCHEMA_CONFIGURATION_ID => self::OPTION_VALUE_NO_VALUE_ID,
            ],
            self::SCHEMA_NAMESPACING => [
                self::OPTION_USE_NAMESPACING => false,
            ],
            self::PUBLIC_PRIVATE_SCHEMA => [
                self::OPTION_MODE => SchemaModes::PUBLIC_SCHEMA_MODE,
                self::OPTION_ENABLE_GRANULAR => true,
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
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        // Do the if one by one, so that the SELECT do not get evaluated unless needed
        if ($module == self::SCHEMA_CONFIGURATION) {
            $whereModules = [];
            $maybeWhereModules = [
                EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS,
                EndpointFunctionalityModuleResolver::PERSISTED_QUERIES,
            ];
            foreach ($maybeWhereModules as $maybeWhereModule) {
                if ($moduleRegistry->isModuleEnabled($maybeWhereModule)) {
                    $whereModules[] = '▹ ' . $moduleRegistry->getModuleResolver($maybeWhereModule)->getName($maybeWhereModule);
                }
            }
            // Build all the possible values by fetching all the Schema Configuration posts
            $possibleValues = [
                self::OPTION_VALUE_NO_VALUE_ID => \__('None', 'graphql-api'),
            ];
            /**
             * @var WP_Post[]
             */
            $customPosts = \get_posts([
                'posts_per_page' => -1,
                'post_type' => GraphQLSchemaConfigurationPostType::POST_TYPE,
                'post_status' => 'publish',
            ]);
            if (!empty($customPosts)) {
                foreach ($customPosts as $customPost) {
                    $possibleValues[$customPost->ID] = $customPost->post_title;
                }
            }
            $option = self::OPTION_SCHEMA_CONFIGURATION_ID;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Default Schema Configuration', 'graphql-api'),
                Properties::DESCRIPTION => sprintf(
                    \__('Schema Configuration to use when option <code>"Default"</code> is selected (in %s)', 'graphql-api'),
                    implode(
                        \__(', ', 'graphql-api'),
                        $whereModules
                    )
                ),
                Properties::TYPE => Properties::TYPE_INT,
                // Fetch all Schema Configurations from the DB
                Properties::POSSIBLE_VALUES => $possibleValues,
            ];
        } elseif ($module == self::SCHEMA_NAMESPACING) {
            $option = self::OPTION_USE_NAMESPACING;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Use namespacing?', 'graphql-api'),
                Properties::DESCRIPTION => \__('Automatically namespace types and interfaces in the schema', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        } elseif ($module == self::PUBLIC_PRIVATE_SCHEMA) {
            $whereModules = [
                SchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION,
                AccessControlFunctionalityModuleResolver::ACCESS_CONTROL,
            ];
            $whereModuleNames = array_map(
                fn ($whereModule) => '▹ ' . $moduleRegistry->getModuleResolver($whereModule)->getName($whereModule),
                $whereModules
            );
            $option = self::OPTION_MODE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Default visibility', 'graphql-api'),
                Properties::DESCRIPTION => sprintf(
                    \__('Visibility to use for fields and directives in the schema when option <code>"%s"</code> is selected (in %s)', 'graphql-api'),
                    ComponentConfiguration::getSettingsValueLabel(),
                    implode(
                        \__(', ', 'graphql-api'),
                        $whereModuleNames
                    )
                ),
                Properties::TYPE => Properties::TYPE_STRING,
                Properties::POSSIBLE_VALUES => [
                    SchemaModes::PUBLIC_SCHEMA_MODE => \__('Public', 'graphql-api'),
                    SchemaModes::PRIVATE_SCHEMA_MODE => \__('Private', 'graphql-api'),
                ],
            ];
            $option = self::OPTION_ENABLE_GRANULAR;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Enable granular control?', 'graphql-api'),
                Properties::DESCRIPTION => \__('Enable to select the visibility for a set of fields/directives when editing the Access Control List', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        }
        return $moduleSettings;
    }
}
