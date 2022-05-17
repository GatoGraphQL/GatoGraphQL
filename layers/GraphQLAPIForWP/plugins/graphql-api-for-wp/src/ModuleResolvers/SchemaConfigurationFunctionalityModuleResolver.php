<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleResolvers;

use PoP\Root\App;
use GraphQLAPI\GraphQLAPI\Module;
use GraphQLAPI\GraphQLAPI\ModuleConfiguration;
use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;
use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptionValues;
use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLSchemaConfigurationCustomPostType;
use GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes;
use PoP\AccessControl\Schema\SchemaModes;
use WP_Post;

class SchemaConfigurationFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use SchemaConfigurationFunctionalityModuleResolverTrait;

    public final const SCHEMA_CONFIGURATION = Plugin::NAMESPACE . '\schema-configuration';
    public final const SCHEMA_NAMESPACING = Plugin::NAMESPACE . '\schema-namespacing';
    public final const PUBLIC_PRIVATE_SCHEMA = Plugin::NAMESPACE . '\public-private-schema';
    public final const NESTED_MUTATIONS = Plugin::NAMESPACE . '\nested-mutations';

    private ?GraphQLSchemaConfigurationCustomPostType $graphQLSchemaConfigurationCustomPostType = null;
    private ?MarkdownContentParserInterface $markdownContentParser = null;

    final public function setGraphQLSchemaConfigurationCustomPostType(GraphQLSchemaConfigurationCustomPostType $graphQLSchemaConfigurationCustomPostType): void
    {
        $this->graphQLSchemaConfigurationCustomPostType = $graphQLSchemaConfigurationCustomPostType;
    }
    final protected function getGraphQLSchemaConfigurationCustomPostType(): GraphQLSchemaConfigurationCustomPostType
    {
        return $this->graphQLSchemaConfigurationCustomPostType ??= $this->instanceManager->getInstance(GraphQLSchemaConfigurationCustomPostType::class);
    }
    final public function setMarkdownContentParser(MarkdownContentParserInterface $markdownContentParser): void
    {
        $this->markdownContentParser = $markdownContentParser;
    }
    final protected function getMarkdownContentParser(): MarkdownContentParserInterface
    {
        return $this->markdownContentParser ??= $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
    }

    /**
     * Setting options
     */
    public final const OPTION_MODE = 'mode';
    public final const OPTION_ENABLE_GRANULAR = 'granular';

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::SCHEMA_CONFIGURATION,
            self::SCHEMA_NAMESPACING,
            self::NESTED_MUTATIONS,
            self::PUBLIC_PRIVATE_SCHEMA,
        ];
    }

    /**
     * @return array<array> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     */
    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::SCHEMA_CONFIGURATION:
                return [];
            case self::SCHEMA_NAMESPACING:
            case self::NESTED_MUTATIONS:
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
            self::NESTED_MUTATIONS => \__('Nested Mutations', 'graphql-api'),
        ];
        return $names[$module] ?? $module;
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::SCHEMA_CONFIGURATION:
                return \__('Customize the schema accessible to different Custom Endpoints and Persisted Queries, by applying a custom configuration (involving namespacing, access control, cache control, and others) to the grand schema', 'graphql-api');
            case self::SCHEMA_NAMESPACING:
                return \__('Automatically namespace types with a vendor/project name, to avoid naming collisions', 'graphql-api');
            case self::PUBLIC_PRIVATE_SCHEMA:
                return \__('Enable to communicate the existence of some field from the schema to certain users only (private mode) or to everyone (public mode). If disabled, fields are always available to everyone (public mode)', 'graphql-api');
            case self::NESTED_MUTATIONS:
                return \__('Execute mutations from any type in the schema, not only from the root', 'graphql-api');
        }
        return parent::getDescription($module);
    }

    /**
     * Default value for an option set by the module
     */
    public function getSettingsDefaultValue(string $module, string $option): mixed
    {
        $defaultValues = [
            self::SCHEMA_CONFIGURATION => [
                ModuleSettingOptions::DEFAULT_VALUE => ModuleSettingOptionValues::NO_VALUE_ID,
                ModuleSettingOptions::VALUE_FOR_SINGLE_ENDPOINT => ModuleSettingOptionValues::NO_VALUE_ID,
            ],
            self::SCHEMA_NAMESPACING => [
                ModuleSettingOptions::DEFAULT_VALUE => false,
                ModuleSettingOptions::VALUE_FOR_ADMIN_CLIENTS => false,
            ],
            self::PUBLIC_PRIVATE_SCHEMA => [
                self::OPTION_MODE => SchemaModes::PUBLIC_SCHEMA_MODE,
                self::OPTION_ENABLE_GRANULAR => true,
            ],
            self::NESTED_MUTATIONS => [
                ModuleSettingOptions::DEFAULT_VALUE => MutationSchemes::STANDARD,
                ModuleSettingOptions::VALUE_FOR_ADMIN_CLIENTS => MutationSchemes::STANDARD,
            ],
        ];
        return $defaultValues[$module][$option] ?? null;
    }

    /**
     * Array with the inputs to show as settings for the module
     *
     * @return array<array> List of settings for the module, each entry is an array with property => value
     */
    public function getSettings(string $module): array
    {
        /** @var ModuleConfiguration */
        $componentConfiguration = App::getComponent(Module::class)->getConfiguration();
        $moduleSettings = parent::getSettings($module);
        $defaultValueLabel = $this->getDefaultValueLabel();
        $defaultValueDesc = $this->getDefaultValueDescription();
        $adminClientsDesc = $this->getAdminClientDescription();
        $adminClientAndConfigDesc = $this->getAdminClientAndConfigurationDescription();
        // Do the if one by one, so that the SELECT do not get evaluated unless needed
        if ($module === self::SCHEMA_CONFIGURATION) {
            $whereModules = [];
            $maybeWhereModules = [
                EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS,
                EndpointFunctionalityModuleResolver::PERSISTED_QUERIES,
            ];
            foreach ($maybeWhereModules as $maybeWhereModule) {
                if ($this->getModuleRegistry()->isModuleEnabled($maybeWhereModule)) {
                    $whereModules[] = '▹ ' . $this->getModuleRegistry()->getModuleResolver($maybeWhereModule)->getName($maybeWhereModule);
                }
            }
            // Build all the possible values by fetching all the Schema Configuration posts
            $possibleValues = [
                ModuleSettingOptionValues::NO_VALUE_ID => \__('None', 'graphql-api'),
            ];
            /** @var GraphQLSchemaConfigurationCustomPostType */
            $graphQLSchemaConfigurationCustomPostType = $this->getGraphQLSchemaConfigurationCustomPostType();
            /**
             * @var WP_Post[]
             */
            $customPosts = \get_posts([
                'posts_per_page' => -1,
                'post_type' => $graphQLSchemaConfigurationCustomPostType->getCustomPostType(),
                'post_status' => 'publish',
            ]);
            if (!empty($customPosts)) {
                foreach ($customPosts as $customPost) {
                    $possibleValues[$customPost->ID] = $customPost->post_title;
                }
            }
            $option = ModuleSettingOptions::DEFAULT_VALUE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Default Schema Configuration', 'graphql-api'),
                Properties::DESCRIPTION => sprintf(
                    \__('Schema Configuration to use in %s when option <code>"Default"</code> is selected', 'graphql-api'),
                    implode(
                        \__(', ', 'graphql-api'),
                        $whereModules
                    )
                ),
                Properties::TYPE => Properties::TYPE_INT,
                // Fetch all Schema Configurations from the DB
                Properties::POSSIBLE_VALUES => $possibleValues,
            ];
            if ($this->getModuleRegistry()->isModuleEnabled(EndpointFunctionalityModuleResolver::SINGLE_ENDPOINT)) {
                $option = ModuleSettingOptions::VALUE_FOR_SINGLE_ENDPOINT;
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => \__('Schema Configuration for the Single Endpoint', 'graphql-api'),
                    Properties::DESCRIPTION => \__('Schema Configuration to use in the Single Endpoint', 'graphql-api'),
                    Properties::TYPE => Properties::TYPE_INT,
                    // Fetch all Schema Configurations from the DB
                    Properties::POSSIBLE_VALUES => $possibleValues,
                ];
            }
        } elseif ($module === self::SCHEMA_NAMESPACING) {
            $option = ModuleSettingOptions::DEFAULT_VALUE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => sprintf(
                    \__('Namespace the schema? %s', 'graphql-api'),
                    $defaultValueLabel
                ),
                Properties::DESCRIPTION => sprintf(
                    \__('Namespace types in the GraphQL schema? %s', 'graphql-api'),
                    $defaultValueDesc
                ),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
            $option = ModuleSettingOptions::VALUE_FOR_ADMIN_CLIENTS;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Namespace the schema for the Admin?', 'graphql-api'),
                Properties::DESCRIPTION => sprintf(
                    \__('Namespace the schema in the wp-admin? %s', 'graphql-api'),
                    $adminClientAndConfigDesc
                ),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        } elseif ($module === self::PUBLIC_PRIVATE_SCHEMA) {
            $whereModules = [
                SchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION,
                AccessControlFunctionalityModuleResolver::ACCESS_CONTROL,
            ];
            $whereModuleNames = array_map(
                fn ($whereModule) => '▹ ' . $this->getModuleRegistry()->getModuleResolver($whereModule)->getName($whereModule),
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
                    $componentConfiguration->getSettingsValueLabel(),
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
        } elseif ($module === self::NESTED_MUTATIONS) {
            $possibleValues = [
                MutationSchemes::STANDARD => \__('Do not enable nested mutations', 'graphql-api'),
                MutationSchemes::NESTED_WITH_REDUNDANT_ROOT_FIELDS => \__('Enable nested mutations, keeping all mutation fields in the root', 'graphql-api'),
                MutationSchemes::NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS => \__('Enable nested mutations, removing the redundant mutation fields from the root', 'graphql-api'),
            ];
            $option = ModuleSettingOptions::DEFAULT_VALUE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => sprintf(
                    \__('Mutation Scheme: %s', 'graphql-api'),
                    $defaultValueLabel
                ),
                Properties::DESCRIPTION => $defaultValueDesc,
                Properties::TYPE => Properties::TYPE_STRING,
                Properties::POSSIBLE_VALUES => $possibleValues,
            ];
            $option = ModuleSettingOptions::VALUE_FOR_ADMIN_CLIENTS;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Mutation Scheme for the Admin', 'graphql-api'),
                Properties::DESCRIPTION => sprintf(
                    \__('Select the mutation scheme to use in the wp-admin. %s', 'graphql-api'),
                    $adminClientsDesc
                ),
                Properties::TYPE => Properties::TYPE_STRING,
                Properties::POSSIBLE_VALUES => $possibleValues,
            ];
            $moduleSettings[] = [
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    'intro'
                ),
                Properties::TITLE => \__('Info: Redundant fields', 'graphql-api'),
                Properties::DESCRIPTION => \__('With nested mutations, a mutation operation in the root type may be considered redundant, so it could be removed from the schema.<br/>For instance, if mutation field <code>Post.update</code> is available, mutation field <code>Root.updatePost</code> could be removed', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_NULL,
            ];
        }
        return $moduleSettings;
    }
}
