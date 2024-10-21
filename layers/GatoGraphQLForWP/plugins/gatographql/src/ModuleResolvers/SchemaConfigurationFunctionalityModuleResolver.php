<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Constants\GlobalFieldsSchemaExposure;
use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;
use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;
use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\StaticHelpers\BehaviorHelpers;
use GraphQLByPoP\GraphQLServer\Configuration\MutationPayloadTypeOptions;
use GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes;

class SchemaConfigurationFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use SchemaConfigurationFunctionalityModuleResolverTrait;

    public final const SCHEMA_CONFIGURATION = Plugin::NAMESPACE . '\schema-configuration';
    public final const SCHEMA_NAMESPACING = Plugin::NAMESPACE . '\schema-namespacing';
    public final const RESPONSE_HEADERS = Plugin::NAMESPACE . '\response-headers';
    public final const GLOBAL_FIELDS = Plugin::NAMESPACE . '\global-fields';
    public final const COMPOSABLE_DIRECTIVES = Plugin::NAMESPACE . '\composable-directives';
    public final const MULTIFIELD_DIRECTIVES = Plugin::NAMESPACE . '\multifield-directives';
    public final const MUTATIONS = Plugin::NAMESPACE . '\mutations';
    public final const NESTED_MUTATIONS = Plugin::NAMESPACE . '\nested-mutations';
    public final const SCHEMA_EXPOSE_SENSITIVE_DATA = Plugin::NAMESPACE . '\schema-expose-sensitive-data';
    public final const SCHEMA_SELF_FIELDS = Plugin::NAMESPACE . '\schema-self-fields';
    public final const GLOBAL_ID_FIELD = Plugin::NAMESPACE . '\global-id-field';

    /**
     * Setting options
     */
    public final const OPTION_DEFAULT_SCHEMA_EXPOSURE = 'default-schema-exposure';
    public final const OPTION_USE_PAYLOADABLE_MUTATIONS_DEFAULT_VALUE = 'use-payloadable-mutations-default-value';

    private ?MarkdownContentParserInterface $markdownContentParser = null;

    final public function setMarkdownContentParser(MarkdownContentParserInterface $markdownContentParser): void
    {
        $this->markdownContentParser = $markdownContentParser;
    }
    final protected function getMarkdownContentParser(): MarkdownContentParserInterface
    {
        if ($this->markdownContentParser === null) {
            /** @var MarkdownContentParserInterface */
            $markdownContentParser = $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
            $this->markdownContentParser = $markdownContentParser;
        }
        return $this->markdownContentParser;
    }

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::SCHEMA_CONFIGURATION,
            self::SCHEMA_EXPOSE_SENSITIVE_DATA,
            self::RESPONSE_HEADERS,
            self::MUTATIONS,
            self::NESTED_MUTATIONS,
            self::SCHEMA_SELF_FIELDS,
            self::GLOBAL_ID_FIELD,
            self::SCHEMA_NAMESPACING,
            self::GLOBAL_FIELDS,
            self::COMPOSABLE_DIRECTIVES,
            self::MULTIFIELD_DIRECTIVES,
        ];
    }

    /**
     * @return array<string[]> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     */
    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::NESTED_MUTATIONS:
                return [
                    [
                        self::MUTATIONS,
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::SCHEMA_CONFIGURATION => \__('Schema Configuration', 'gatographql'),
            self::SCHEMA_NAMESPACING => \__('Schema Namespacing', 'gatographql'),
            self::RESPONSE_HEADERS => \__('Response Headers', 'gatographql'),
            self::GLOBAL_FIELDS => \__('Global Fields', 'gatographql'),
            self::COMPOSABLE_DIRECTIVES => \__('Composable Directives', 'gatographql'),
            self::MULTIFIELD_DIRECTIVES => \__('Multi-Field Directives', 'gatographql'),
            self::MUTATIONS => \__('Mutations', 'gatographql'),
            self::NESTED_MUTATIONS => \__('Nested Mutations', 'gatographql'),
            self::SCHEMA_EXPOSE_SENSITIVE_DATA => \__('Expose Sensitive Data in the Schema', 'gatographql'),
            self::SCHEMA_SELF_FIELDS => \__('Self Fields', 'gatographql'),
            self::GLOBAL_ID_FIELD => \__('Global ID Field', 'gatographql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::SCHEMA_CONFIGURATION => \__('Customize the schema accessible to different endpoints, by applying a custom configuration (involving namespacing, access control, cache control, and others) to the grand schema', 'gatographql'),
            self::SCHEMA_NAMESPACING => \__('Automatically namespace types with a vendor/project name, to avoid naming collisions', 'gatographql'),
            self::RESPONSE_HEADERS => \__('Provide custom headers to add to the API response', 'gatographql'),
            self::GLOBAL_FIELDS => \__('Fields added to all types in the schema, generally for executing functionality (not retrieving data)', 'gatographql'),
            self::COMPOSABLE_DIRECTIVES => \__('Have directives modify the behavior of other directives', 'gatographql'),
            self::MULTIFIELD_DIRECTIVES => \__('A single directive can be applied to multiple fields, for performance and extended use cases', 'gatographql'),
            self::MUTATIONS => \__('Modify data by executing mutations', 'gatographql'),
            self::NESTED_MUTATIONS => \__('Execute mutations from any type in the schema, not only from the root', 'gatographql'),
            self::SCHEMA_EXPOSE_SENSITIVE_DATA => \__('Expose “sensitive” data elements in the schema', 'gatographql'),
            self::SCHEMA_SELF_FIELDS => \__('Expose "self" fields in the schema', 'gatographql'),
            self::GLOBAL_ID_FIELD => \__('Uniquely identify objects via field <code>globalID</code> on all types of the GraphQL schema', 'gatographql'),
            default => parent::getDescription($module),
        };
    }

    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return match ($module) {
            self::GLOBAL_FIELDS => true,
            // self::GLOBAL_ID_FIELD => true,
            default => parent::isPredefinedEnabledOrDisabled($module),
        };
    }

    public function isHidden(string $module): bool
    {
        return match ($module) {
            self::GLOBAL_ID_FIELD => true,
            default => parent::isHidden($module),
        };
    }

    public function isEnabledByDefault(string $module): bool
    {
        /**
         * @var ModuleConfiguration
         */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return match ($module) {
            self::SCHEMA_CONFIGURATION => $moduleConfiguration->isSchemaConfigurationModuleEnabledByDefault(),
            default => parent::isEnabledByDefault($module),
        };
    }

    /**
     * Default value for an option set by the module
     */
    public function getSettingsDefaultValue(string $module, string $option): mixed
    {
        $useRestrictiveDefaults = BehaviorHelpers::areRestrictiveDefaultsEnabled();
        $defaultValues = [
            self::SCHEMA_NAMESPACING => [
                ModuleSettingOptions::DEFAULT_VALUE => false,
            ],
            self::RESPONSE_HEADERS => [
                ModuleSettingOptions::ENTRIES => [],
            ],
            self::GLOBAL_FIELDS => [
                self::OPTION_DEFAULT_SCHEMA_EXPOSURE => GlobalFieldsSchemaExposure::EXPOSE_IN_ROOT_TYPE_ONLY,
            ],
            self::MULTIFIELD_DIRECTIVES => [
                ModuleSettingOptions::DEFAULT_VALUE => true,
            ],
            self::COMPOSABLE_DIRECTIVES => [
                ModuleSettingOptions::DEFAULT_VALUE => true,
            ],
            self::MUTATIONS => [
                self::OPTION_USE_PAYLOADABLE_MUTATIONS_DEFAULT_VALUE => MutationPayloadTypeOptions::USE_PAYLOAD_TYPES_FOR_MUTATIONS,
            ],
            self::NESTED_MUTATIONS => [
                ModuleSettingOptions::DEFAULT_VALUE => MutationSchemes::STANDARD,
            ],
            self::SCHEMA_EXPOSE_SENSITIVE_DATA => [
                ModuleSettingOptions::DEFAULT_VALUE => !$useRestrictiveDefaults,
            ],
            self::SCHEMA_SELF_FIELDS => [
                ModuleSettingOptions::DEFAULT_VALUE => true,
            ],
        ];
        return $defaultValues[$module][$option] ?? null;
    }

    /**
     * Array with the inputs to show as settings for the module
     *
     * @return array<array<string,mixed>> List of settings for the module, each entry is an array with property => value
     */
    public function getSettings(string $module): array
    {
        $moduleSettings = parent::getSettings($module);
        $defaultValueDesc = $this->getDefaultValueDescription($this->getName($module));
        // Do the if one by one, so that the SELECT do not get evaluated unless needed
        if ($module === self::SCHEMA_NAMESPACING) {
            $option = ModuleSettingOptions::DEFAULT_VALUE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Namespace the schema?', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    \__('Namespace types in the GraphQL schema?<br/>%s<br/><span class="more-info">%s</span>', 'gatographql'),
                    $defaultValueDesc,
                    $this->getSettingsItemHelpLinkHTML(
                        'https://gatographql.com/guides/schema/namespacing-the-schema',
                        \__('Namespacing the schema', 'gatographql')
                    )
                ),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        } elseif ($module === self::RESPONSE_HEADERS) {
            $option = ModuleSettingOptions::ENTRIES;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Response Headers', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    '%s<br/>%s<br/><span class="more-info">%s</span>',
                    \__('Provide custom headers to add to the API response. One header per line, with format: <code>{header name}: {header value}</code>', 'gatographql'),
                    $defaultValueDesc,
                    $this->getSettingsItemHelpLinkHTML(
                        'https://gatographql.com/guides/schema/adding-custom-headers-to-the-graphql-response-cors',
                        \__('Adding custom headers to the GraphQL response (CORS)', 'gatographql')
                    )
                ),
                Properties::TYPE => Properties::TYPE_ARRAY,
                Properties::IS_MULTIPLE => true,
            ];
        } elseif ($module === self::GLOBAL_FIELDS) {
            $globalFieldsSchemaExposureValues = [
                GlobalFieldsSchemaExposure::DO_NOT_EXPOSE => \__('Do not expose', 'gatographql'),
                GlobalFieldsSchemaExposure::EXPOSE_IN_ROOT_TYPE_ONLY => \__('Expose under the Root type only', 'gatographql'),
                GlobalFieldsSchemaExposure::EXPOSE_IN_ALL_TYPES => \__('Expose under all types', 'gatographql'),
            ];
            $option = self::OPTION_DEFAULT_SCHEMA_EXPOSURE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Schema exposure.', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    \__('Under what types to expose global fields.<br/>%s<br/><span class="more-info">%s</span>', 'gatographql'),
                    $defaultValueDesc,
                    $this->getSettingsItemHelpLinkHTML(
                        'https://gatographql.com/guides/config/hiding-global-fields',
                        \__('Hiding Global Fields', 'gatographql')
                    )
                ),
                Properties::TYPE => Properties::TYPE_STRING,
                Properties::POSSIBLE_VALUES => $globalFieldsSchemaExposureValues,
            ];
        } elseif ($module === self::MULTIFIELD_DIRECTIVES) {
            $option = ModuleSettingOptions::DEFAULT_VALUE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Enable multi-field directives?', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    \__('Enable having a single directive be applied to multiple fields.<br/>%s<br/><span class="more-info">%s</span>', 'gatographql'),
                    $defaultValueDesc,
                    $this->getSettingsItemHelpLinkHTML(
                        'https://gatographql.com/guides/schema/using-multi-field-directives',
                        \__('Using multi-field directives', 'gatographql')
                    )
                ),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        } elseif ($module === self::COMPOSABLE_DIRECTIVES) {
            $option = ModuleSettingOptions::DEFAULT_VALUE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Enable composable directives?', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    \__('Enable adding composable directives (also called "meta-directives", such as <code>@underEachArrayItem</code>, <code>@underArrayItem</code> and <code>@underJSONObjectProperty</code>) to the schema.<br/>%s<br/><span class="more-info">%s</span>', 'gatographql'),
                    $defaultValueDesc,
                    $this->getSettingsItemHelpLinkHTML(
                        'https://gatographql.com/guides/schema/using-composable-directives',
                        \__('Using composable directives', 'gatographql')
                    )
                ),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        } elseif ($module === self::MUTATIONS) {
            $possibleValues = [
                MutationPayloadTypeOptions::USE_PAYLOAD_TYPES_FOR_MUTATIONS => \__('Use payload types for mutations', 'gatographql'),
                MutationPayloadTypeOptions::USE_AND_QUERY_PAYLOAD_TYPES_FOR_MUTATIONS => \__('Use payload types for mutations, and add fields to query those payload objects', 'gatographql'),
                MutationPayloadTypeOptions::DO_NOT_USE_PAYLOAD_TYPES_FOR_MUTATIONS => \__('Do not use payload types for mutations (i.e. return the mutated entity)', 'gatographql'),
            ];
            $option = self::OPTION_USE_PAYLOADABLE_MUTATIONS_DEFAULT_VALUE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Use payload types for all mutations in the schema?', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    \__('Use payload types for mutations in the schema?<br/>%s<br/><span class="more-info">%s</span>', 'gatographql'),
                    $defaultValueDesc,
                    $this->getSettingsItemHelpLinkHTML(
                        'https://gatographql.com/guides/config/returning-a-payload-object-or-the-mutated-entity-for-mutations',
                        \__('Returning a payload object or the mutated entity for mutations', 'gatographql')
                    )
                ),
                Properties::TYPE => Properties::TYPE_STRING,
                Properties::POSSIBLE_VALUES => $possibleValues,
            ];

            $moduleSettings[] = [
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    'payload-types-intro'
                ),
                Properties::DESCRIPTION => \__('<strong>Use payload types for mutations</strong>:<br/><br/>Mutation fields will return a payload object type, on which we can query the status of the mutation (success or failure), and the error messages (if any) or the successfully mutated entity.<br/><br/><strong>Use payload types for mutations, and add fields to query those payload objects</strong>:<br/><br/>In addition, query fields will be added to retrieve once again those payload objects.<br/><br/><strong>Do not use payload types for mutations (i.e. return the mutated entity)</strong>:<br/><br/>Mutation fields will directly return the mutated entity in case of success or <code>null</code> in case of failure, and any error message will be displayed in the JSON response\'s top-level <code>errors</code> entry.</li></ul>', 'gatographql'),
                Properties::TYPE => Properties::TYPE_NULL,
            ];
        } elseif ($module === self::NESTED_MUTATIONS) {
            $possibleValues = [
                MutationSchemes::STANDARD => \__('Do not enable nested mutations', 'gatographql'),
                MutationSchemes::NESTED_WITH_REDUNDANT_ROOT_FIELDS => \__('Enable nested mutations, keeping all mutation fields in the root', 'gatographql'),
                MutationSchemes::NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS => \__('Enable nested mutations, removing the redundant mutation fields from the root', 'gatographql'),
            ];
            $option = ModuleSettingOptions::DEFAULT_VALUE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Mutation Scheme:', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    \__('Select the mutation scheme to use in the schema.<br/>%s<br/><span class="more-info">%s</span>', 'gatographql'),
                    $defaultValueDesc,
                    $this->getSettingsItemHelpLinkHTML(
                        'https://gatographql.com/guides/schema/using-nested-mutations',
                        \__('Using nested mutations', 'gatographql')
                    )
                ),
                Properties::TYPE => Properties::TYPE_STRING,
                Properties::POSSIBLE_VALUES => $possibleValues,
            ];
            $moduleSettings[] = [
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    'intro'
                ),
                Properties::DESCRIPTION => \__('<strong>Redundant fields:</strong><br/><br/>When nested mutations are enabled, a mutation operation in the <code>Root</code> type may find that another mutation will execute the same operation. As such, the <code>Root</code> mutation could be considered redundant, and removed from the schema.<br/><br/>For instance, if mutation field <code>Post.update</code> is available, mutation field <code>Root.updatePost</code> could be removed.', 'gatographql'),
                Properties::TYPE => Properties::TYPE_NULL,
            ];
        } elseif ($module === self::SCHEMA_EXPOSE_SENSITIVE_DATA) {
            $option = ModuleSettingOptions::DEFAULT_VALUE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Add “sensitive” fields to the schema?', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    \__('Expose “sensitive” data elements in the GraphQL schema (such as field <code>Root.roles</code>, field arg <code>Root.posts(status:)</code>, and others), which provide access to potentially private user data.<br/>%s<br/><span class="more-info">%s</span>', 'gatographql'),
                    $defaultValueDesc,
                    $this->getSettingsItemHelpLinkHTML(
                        'https://gatographql.com/guides/schema/querying-sensitive-data-fields',
                        \__('Querying “sensitive” data fields', 'gatographql')
                    )
                ),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        } elseif ($module === self::SCHEMA_SELF_FIELDS) {
            $option = ModuleSettingOptions::DEFAULT_VALUE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Expose the self fields to all types in the schema?', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    \__('Expose the <code>self</code> field in the GraphQL schema, which returns an instance of the same object (for whichever type it is applied on), which can be used to adapt the shape of the GraphQL response.<br/>%s<br/><span class="more-info">%s</span>', 'gatographql'),
                    $defaultValueDesc,
                    $this->getSettingsItemHelpLinkHTML(
                        'https://gatographql.com/guides/schema/querying-self-fields',
                        \__('Querying \'self\' fields', 'gatographql')
                    )
                ),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        }
        return $moduleSettings;
    }
}
