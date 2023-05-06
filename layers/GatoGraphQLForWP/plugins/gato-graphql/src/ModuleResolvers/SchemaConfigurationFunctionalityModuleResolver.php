<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\Constants\ModuleSettingOptions;
use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;
use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;
use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\StaticHelpers\BehaviorHelpers;
use GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes;

class SchemaConfigurationFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use SchemaConfigurationFunctionalityModuleResolverTrait;

    public final const SCHEMA_CONFIGURATION = Plugin::NAMESPACE . '\schema-configuration';
    public final const SCHEMA_NAMESPACING = Plugin::NAMESPACE . '\schema-namespacing';
    public final const MUTATIONS = Plugin::NAMESPACE . '\mutations';
    public final const NESTED_MUTATIONS = Plugin::NAMESPACE . '\nested-mutations';
    public final const SCHEMA_EXPOSE_SENSITIVE_DATA = Plugin::NAMESPACE . '\schema-expose-sensitive-data';
    public final const SCHEMA_SELF_FIELDS = Plugin::NAMESPACE . '\schema-self-fields';
    public final const GLOBAL_ID_FIELD = Plugin::NAMESPACE . '\global-id-field';

    /**
     * Setting options
     */
    public final const USE_PAYLOADABLE_MUTATIONS_DEFAULT_VALUE = 'use-payloadable-mutations-default-value';

    private ?MarkdownContentParserInterface $markdownContentParser = null;

    final public function setMarkdownContentParser(MarkdownContentParserInterface $markdownContentParser): void
    {
        $this->markdownContentParser = $markdownContentParser;
    }
    final protected function getMarkdownContentParser(): MarkdownContentParserInterface
    {
        /** @var MarkdownContentParserInterface */
        return $this->markdownContentParser ??= $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
    }

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::SCHEMA_CONFIGURATION,
            self::SCHEMA_NAMESPACING,
            self::MUTATIONS,
            self::NESTED_MUTATIONS,
            self::SCHEMA_EXPOSE_SENSITIVE_DATA,
            self::SCHEMA_SELF_FIELDS,
            self::GLOBAL_ID_FIELD,
        ];
    }

    /**
     * @return array<string[]> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     */
    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::SCHEMA_CONFIGURATION:
            case self::MUTATIONS:
            case self::SCHEMA_NAMESPACING:
                return [];
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
            self::SCHEMA_CONFIGURATION => \__('Schema Configuration', 'gato-graphql'),
            self::SCHEMA_NAMESPACING => \__('Schema Namespacing', 'gato-graphql'),
            self::MUTATIONS => \__('Mutations', 'gato-graphql'),
            self::NESTED_MUTATIONS => \__('Nested Mutations', 'gato-graphql'),
            self::SCHEMA_EXPOSE_SENSITIVE_DATA => \__('Expose Sensitive Data in the Schema', 'gato-graphql'),
            self::SCHEMA_SELF_FIELDS => \__('Self Fields', 'gato-graphql'),
            self::GLOBAL_ID_FIELD => \__('Global ID Field', 'gato-graphql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::SCHEMA_CONFIGURATION => \__('Customize the schema accessible to different Custom Endpoints and Persisted Queries, by applying a custom configuration (involving namespacing, access control, cache control, and others) to the grand schema', 'gato-graphql'),
            self::SCHEMA_NAMESPACING => \__('Automatically namespace types with a vendor/project name, to avoid naming collisions', 'gato-graphql'),
            self::MUTATIONS => \__('Modify data by executing mutations', 'gato-graphql'),
            self::NESTED_MUTATIONS => \__('Execute mutations from any type in the schema, not only from the root', 'gato-graphql'),
            self::SCHEMA_EXPOSE_SENSITIVE_DATA => \__('Expose “sensitive” data elements in the schema', 'gato-graphql'),
            self::SCHEMA_SELF_FIELDS => \__('Expose "self" fields in the schema', 'gato-graphql'),
            self::GLOBAL_ID_FIELD => \__('Uniquely identify objects via field <code>globalID</code> on all types of the GraphQL schema', 'gato-graphql'),
            default => parent::getDescription($module),
        };
    }

    public function isPredefinedEnabledOrDisabled(string $module): ?bool
    {
        return match ($module) {
            self::GLOBAL_ID_FIELD => true,
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
            self::MUTATIONS => [
                self::USE_PAYLOADABLE_MUTATIONS_DEFAULT_VALUE => true,
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
                Properties::TITLE => \__('Namespace the schema?', 'gato-graphql'),
                Properties::DESCRIPTION => sprintf(
                    \__('Namespace types in the GraphQL schema?<br/>%s', 'gato-graphql'),
                    $defaultValueDesc
                ),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        } elseif ($module === self::MUTATIONS) {
            $option = self::USE_PAYLOADABLE_MUTATIONS_DEFAULT_VALUE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Use payload types for all mutations in the schema?', 'gato-graphql'),
                Properties::DESCRIPTION => sprintf(
                    \__('Use payload types for mutations in the schema?<br/>%s', 'gato-graphql'),
                    $defaultValueDesc
                ),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];

            $moduleSettings[] = [
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    'payload-types-intro'
                ),
                Properties::DESCRIPTION => \__('✅ <strong>Checked</strong>:<br/><br/>Mutation fields will return a payload object type, on which we can query the status of the mutation (success or failure), and the error messages (if any) or the successfully mutated entity.<br/><br/>🟥 <strong>Unchecked</strong>:<br/><br/>Mutation fields will directly return the mutated entity in case of success or <code>null</code> in case of failure, and any error message will be displayed in the JSON response\'s top-level <code>errors</code> entry.</li></ul>', 'gato-graphql'),
                Properties::TYPE => Properties::TYPE_NULL,
            ];
        } elseif ($module === self::NESTED_MUTATIONS) {
            $possibleValues = [
                MutationSchemes::STANDARD => \__('Do not enable nested mutations', 'gato-graphql'),
                MutationSchemes::NESTED_WITH_REDUNDANT_ROOT_FIELDS => \__('Enable nested mutations, keeping all mutation fields in the root', 'gato-graphql'),
                MutationSchemes::NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS => \__('Enable nested mutations, removing the redundant mutation fields from the root', 'gato-graphql'),
            ];
            $option = ModuleSettingOptions::DEFAULT_VALUE;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Mutation Scheme:', 'gato-graphql'),
                Properties::DESCRIPTION => sprintf(
                    \__('Select the mutation scheme to use in the schema.<br/>%s', 'gato-graphql'),
                    $defaultValueDesc
                ),
                Properties::TYPE => Properties::TYPE_STRING,
                Properties::POSSIBLE_VALUES => $possibleValues,
            ];
            $moduleSettings[] = [
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    'intro'
                ),
                Properties::DESCRIPTION => \__('<strong>Redundant fields:</strong><br/><br/>When nested mutations are enabled, a mutation operation in the <code>Root</code> type may find that another mutation will execute the same operation. As such, the <code>Root</code> mutation could be considered redundant, and removed from the schema.<br/><br/>For instance, if mutation field <code>Post.update</code> is available, mutation field <code>Root.updatePost</code> could be removed.', 'gato-graphql'),
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
                Properties::TITLE => \__('Add “sensitive” fields to the schema?', 'gato-graphql'),
                Properties::DESCRIPTION => sprintf(
                    \__('Expose “sensitive” data elements in the GraphQL schema (such as field <code>Root.roles</code>, field arg <code>Root.posts(status:)</code>, and others), which provide access to potentially private user data.<br/>%s', 'gato-graphql'),
                    $defaultValueDesc
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
                Properties::TITLE => \__('Expose the self fields to all types in the schema?', 'gato-graphql'),
                Properties::DESCRIPTION => sprintf(
                    \__('Expose the <code>self</code> field in the GraphQL schema, which returns an instance of the same object (for whichever type it is applied on), which can be used to adapt the shape of the GraphQL response.<br/>%s', 'gato-graphql'),
                    $defaultValueDesc
                ),
                Properties::TYPE => Properties::TYPE_BOOL,
            ];
        }
        return $moduleSettings;
    }
}
