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
    public final const GRAPHQL_SPEC_COMPLIANCE = Plugin::NAMESPACE . '\graphql-spec-compliance';

    /**
     * Setting options
     */
    public final const OPTION_DEFAULT_SCHEMA_EXPOSURE = 'default-schema-exposure';
    public final const OPTION_USE_PAYLOADABLE_MUTATIONS_DEFAULT_VALUE = 'use-payloadable-mutations-default-value';
    public final const OPTION_PROPAGATE_NULL_TO_NEAREST_NULLABLE_PARENT = 'propagate-null-to-nearest-nullable-parent';
    public final const OPTION_COERCE_OUTPUT_VALUES = 'coerce-output-values';
    public final const OPTION_ADD_RESPONSE_PATH_TO_ERRORS = 'add-response-path-to-errors';
    public final const OPTION_RETURN_NO_DATA_ON_REQUEST_ERRORS = 'return-no-data-on-request-errors';

    private ?MarkdownContentParserInterface $markdownContentParser = null;

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
            self::GRAPHQL_SPEC_COMPLIANCE,
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
            self::GRAPHQL_SPEC_COMPLIANCE => \__('GraphQL Spec Compliance', 'gatographql'),
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
            self::GRAPHQL_SPEC_COMPLIANCE => \__('Follow the GraphQL spec where Gato GraphQL behaves differently by default: pass a <code>null</code> in a non-null field up to its nearest nullable parent, coerce the scalar and enum values, and add the response path to errors', 'gatographql'),
            default => parent::getDescription($module),
        };
    }

    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return match ($module) {
            self::GLOBAL_FIELDS,
            self::GLOBAL_ID_FIELD
                => true,
            default
                => parent::isPredefinedEnabledOrDisabled($module),
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
            self::GRAPHQL_SPEC_COMPLIANCE => [
                self::OPTION_PROPAGATE_NULL_TO_NEAREST_NULLABLE_PARENT => false,
                self::OPTION_COERCE_OUTPUT_VALUES => false,
                self::OPTION_ADD_RESPONSE_PATH_TO_ERRORS => false,
                self::OPTION_RETURN_NO_DATA_ON_REQUEST_ERRORS => false,
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
                Properties::TITLE => \__('Namespace the schema', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    \__('Add a namespace to the types in the GraphQL schema.%s', 'gatographql'),
                    $defaultValueDesc,
                ),
                Properties::DOCS_URL => 'https://gatographql.com/guides/schema/namespacing-the-schema',
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
                    '%s%s',
                    \__('Custom headers that the API response adds.', 'gatographql'),
                    $defaultValueDesc,
                ),
                Properties::HELP => \__('One header per entry, with format <code>{header name}: {header value}</code>.', 'gatographql'),
                Properties::DOCS_URL => 'https://gatographql.com/guides/schema/adding-custom-headers-to-the-graphql-response-cors',
                Properties::TYPE => Properties::TYPE_ARRAY,
                Properties::IS_MULTIPLE => true,
            ];
        } elseif ($module === self::GLOBAL_FIELDS) {
            $globalFieldsSchemaExposureValues = [
                GlobalFieldsSchemaExposure::DO_NOT_EXPOSE => \__('Do not expose', 'gatographql'),
                GlobalFieldsSchemaExposure::EXPOSE_IN_ROOT_TYPE_ONLY => \__('Root type only', 'gatographql'),
                GlobalFieldsSchemaExposure::EXPOSE_IN_ALL_TYPES => \__('All types', 'gatographql'),
            ];
            $option = self::OPTION_DEFAULT_SCHEMA_EXPOSURE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Schema exposure', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    \__('The types that show the global fields in the schema.%s', 'gatographql'),
                    $defaultValueDesc,
                ),
                Properties::DOCS_URL => 'https://gatographql.com/guides/config/hiding-global-fields',
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
                Properties::TITLE => \__('Enable multi-field directives', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    \__('Apply a single directive to several fields.%s', 'gatographql'),
                    $defaultValueDesc,
                ),
                Properties::DOCS_URL => 'https://gatographql.com/guides/schema/using-multi-field-directives',
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
                Properties::TITLE => \__('Enable composable directives', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    \__('Add composable directives (or "meta-directives") to the schema.%s', 'gatographql'),
                    $defaultValueDesc,
                ),
                Properties::HELP => \__('Such as <code>@underEachArrayItem</code>, <code>@underArrayItem</code> and <code>@underJSONObjectProperty</code>.', 'gatographql'),
                Properties::DOCS_URL => 'https://gatographql.com/guides/schema/using-composable-directives',
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        } elseif ($module === self::MUTATIONS) {
            $possibleValues = [
                MutationPayloadTypeOptions::USE_PAYLOAD_TYPES_FOR_MUTATIONS => \__('Payload types', 'gatographql'),
                MutationPayloadTypeOptions::USE_AND_QUERY_PAYLOAD_TYPES_FOR_MUTATIONS => \__('Payload types + queries', 'gatographql'),
                MutationPayloadTypeOptions::DO_NOT_USE_PAYLOAD_TYPES_FOR_MUTATIONS => \__('Mutated entity', 'gatographql'),
            ];
            $option = self::OPTION_USE_PAYLOADABLE_MUTATIONS_DEFAULT_VALUE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('What mutations return', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    \__('A payload object, or the mutated entity itself.%s', 'gatographql'),
                    $defaultValueDesc,
                ),
                Properties::HELP => \__('<ul><li><strong>Payload types</strong>: each mutation returns a payload object, which holds the status (success or failure), and the errors or the mutated entity.</li><li><strong>Payload types + queries</strong>: the same, and the schema also adds query fields to fetch those payload objects again.</li><li><strong>Mutated entity</strong>: each mutation returns the mutated entity, or <code>null</code> when it fails. The errors go under the top-level <code>errors</code> entry of the response.</li></ul>', 'gatographql'),
                Properties::DOCS_URL => 'https://gatographql.com/guides/config/returning-a-payload-object-or-the-mutated-entity-for-mutations',
                Properties::TYPE => Properties::TYPE_STRING,
                Properties::POSSIBLE_VALUES => $possibleValues,
            ];
        } elseif ($module === self::NESTED_MUTATIONS) {
            $possibleValues = [
                MutationSchemes::STANDARD => \__('Disabled', 'gatographql'),
                MutationSchemes::NESTED_WITH_REDUNDANT_ROOT_FIELDS => \__('Enabled, keep all root fields', 'gatographql'),
                MutationSchemes::NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS => \__('Enabled, drop redundant root fields', 'gatographql'),
            ];
            $option = ModuleSettingOptions::DEFAULT_VALUE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Nested mutations', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    \__('Run mutations on any type, not only on the root.%s', 'gatographql'),
                    $defaultValueDesc,
                ),
                Properties::HELP => \__('With nested mutations, another field may do what a <code>Root</code> mutation does. Then the <code>Root</code> mutation is redundant, and the schema can drop it.<br/><br/>For instance, <code>Post.update</code> makes <code>Root.updatePost</code> redundant.', 'gatographql'),
                Properties::DOCS_URL => 'https://gatographql.com/guides/schema/using-nested-mutations',
                Properties::TYPE => Properties::TYPE_STRING,
                Properties::POSSIBLE_VALUES => $possibleValues,
            ];
        } elseif ($module === self::SCHEMA_EXPOSE_SENSITIVE_DATA) {
            $option = ModuleSettingOptions::DEFAULT_VALUE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Add “sensitive” fields to the schema', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    \__('Show the fields that can give access to private data.%s', 'gatographql'),
                    $defaultValueDesc,
                ),
                Properties::HELP => \__('Such as field <code>Root.roles</code> and field argument <code>Root.posts(status:)</code>.', 'gatographql'),
                Properties::DOCS_URL => 'https://gatographql.com/guides/schema/querying-sensitive-data-fields',
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
                Properties::TITLE => \__('Expose the self fields', 'gatographql'),
                Properties::DESCRIPTION => sprintf(
                    \__('Add field <code>self</code> to all types in the schema.%s', 'gatographql'),
                    $defaultValueDesc,
                ),
                Properties::HELP => \__('Field <code>self</code> returns the same object it applies to, so you can adapt the shape of the response.', 'gatographql'),
                Properties::DOCS_URL => 'https://gatographql.com/guides/schema/querying-self-fields',
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        } elseif ($module === self::GRAPHQL_SPEC_COMPLIANCE) {
            $docsURL = 'https://gatographql.com/guides/schema/using-graphql-spec-compliance';
            $options = [
                self::OPTION_PROPAGATE_NULL_TO_NEAREST_NULLABLE_PARENT => [
                    \__('Pass a null up to the nearest nullable parent', 'gatographql'),
                    \__('A <code>null</code> in a non-null field goes up to its nullable parent.', 'gatographql'),
                    \__('When a non-null field (or an item in a list of non-null items) is <code>null</code>, the response passes that <code>null</code> up to its nearest nullable parent (or to <code>data</code>), as the GraphQL spec defines.<br/><br/>When disabled, the response prints that field as <code>null</code>.', 'gatographql'),
                ],
                self::OPTION_COERCE_OUTPUT_VALUES => [
                    \__('Coerce scalar and enum values', 'gatographql'),
                    \__('Print built-in scalars and enums as their type demands.', 'gatographql'),
                    \__('The response coerces those values to their type, as the GraphQL spec defines: it prints an <code>ID</code> as a string, such as <code>"1"</code>, and adds an error when it cannot coerce a value.<br/><br/>When disabled, the response prints the values as they are, such as <code>1</code> for an <code>ID</code>.', 'gatographql'),
                ],
                self::OPTION_ADD_RESPONSE_PATH_TO_ERRORS => [
                    \__('Add the response path to errors', 'gatographql'),
                    \__('Each error says where in the response it happened.', 'gatographql'),
                    \__('Every error gets entry <code>path</code> with its position in the response, such as <code>["posts", 1, "title"]</code>, as the GraphQL spec defines. Then an error that happens in several positions shows once for each of them.<br/><br/>Gato GraphQL always prints the path of the error in the query, under <code>extensions.path</code>.', 'gatographql'),
                ],
                self::OPTION_RETURN_NO_DATA_ON_REQUEST_ERRORS => [
                    \__('Return no data on request errors', 'gatographql'),
                    \__('A query with errors runs nothing, and returns only the errors.', 'gatographql'),
                    \__('The server validates the whole query against the schema before running it, as the GraphQL spec defines. Errors include a field that does not exist, or an argument value of the wrong type. Then the response holds only the errors, without entry <code>data</code>.<br/><br/>When disabled, the server still resolves the valid fields.', 'gatographql'),
                ],
            ];
            foreach ($options as $option => [$title, $description, $help]) {
                $moduleSettings[] = [
                    Properties::INPUT => $option,
                    Properties::NAME => $this->getSettingOptionName(
                        $module,
                        $option
                    ),
                    Properties::TITLE => $title,
                    Properties::DESCRIPTION => sprintf(
                        '%s%s',
                        $description,
                        $defaultValueDesc
                    ),
                    Properties::HELP => $help,
                    Properties::DOCS_URL => $docsURL,
                    Properties::TYPE => Properties::TYPE_BOOL,
                ];
            }
        }
        return $moduleSettings;
    }
}
