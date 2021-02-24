<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\SystemServices\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes;
use GraphQLAPI\GraphQLAPI\SystemServices\ModuleResolvers\ModuleResolverTrait;
use GraphQLAPI\GraphQLAPI\Services\ModuleTypeResolvers\ModuleTypeResolver;

class OperationalFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;

    public const MULTIPLE_QUERY_EXECUTION = Plugin::NAMESPACE . '\multiple-query-execution';
    public const REMOVE_IF_NULL_DIRECTIVE = Plugin::NAMESPACE . '\remove-if-null-directive';
    public const PROACTIVE_FEEDBACK = Plugin::NAMESPACE . '\proactive-feedback';
    public const EMBEDDABLE_FIELDS = Plugin::NAMESPACE . '\embeddable-fields';
    public const COMPOSABLE_DIRECTIVES = Plugin::NAMESPACE . '\composable-directives';
    public const MUTATIONS = Plugin::NAMESPACE . '\mutations';
    public const NESTED_MUTATIONS = Plugin::NAMESPACE . '\nested-mutations';

    /**
     * Setting options
     */
    public const OPTION_SCHEME = 'scheme';

    /**
     * @return string[]
     */
    public static function getModulesToResolve(): array
    {
        return [
            self::MULTIPLE_QUERY_EXECUTION,
            self::REMOVE_IF_NULL_DIRECTIVE,
            self::PROACTIVE_FEEDBACK,
            self::EMBEDDABLE_FIELDS,
            // Temporarily disabled
            // self::COMPOSABLE_DIRECTIVES,
            self::MUTATIONS,
            self::NESTED_MUTATIONS,
        ];
    }

    /**
     * Enable to customize a specific UI for the module
     */
    public function getModuleType(string $module): string
    {
        return ModuleTypeResolver::OPERATIONAL;
    }

    /**
     * @return array<array> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     */
    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::MULTIPLE_QUERY_EXECUTION:
                return [
                    [
                        EndpointFunctionalityModuleResolver::PERSISTED_QUERIES,
                        EndpointFunctionalityModuleResolver::SINGLE_ENDPOINT,
                        EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS,
                    ],
                ];
            case self::REMOVE_IF_NULL_DIRECTIVE:
            case self::PROACTIVE_FEEDBACK:
            case self::EMBEDDABLE_FIELDS:
            case self::COMPOSABLE_DIRECTIVES:
            case self::MUTATIONS:
                return [];
            case self::NESTED_MUTATIONS:
                return [
                    [
                        self::MUTATIONS,
                    ]
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        $names = [
            self::MULTIPLE_QUERY_EXECUTION => \__('Multiple Query Execution', 'graphql-api'),
            self::REMOVE_IF_NULL_DIRECTIVE => \__('Remove if Null', 'graphql-api'),
            self::PROACTIVE_FEEDBACK => \__('Proactive Feedback', 'graphql-api'),
            self::EMBEDDABLE_FIELDS => \__('Embeddable Fields', 'graphql-api'),
            self::COMPOSABLE_DIRECTIVES => \__('Composable Directives', 'graphql-api'),
            self::MUTATIONS => \__('Mutations', 'graphql-api'),
            self::NESTED_MUTATIONS => \__('Nested Mutations', 'graphql-api'),
        ];
        return $names[$module] ?? $module;
    }

    public function getDescription(string $module): string
    {
        switch ($module) {
            case self::MULTIPLE_QUERY_EXECUTION:
                return \__('Execute multiple GraphQL queries in a single operation', 'graphql-api');
            case self::REMOVE_IF_NULL_DIRECTIVE:
                return \__('Addition of <code>@removeIfNull</code> directive, to remove an output from the response if it is <code>null</code>', 'graphql-api');
            case self::PROACTIVE_FEEDBACK:
                return \__('Usage of the top-level entry <code>extensions</code> to send deprecations, warnings, logs, notices and traces in the response to the query', 'graphql-api');
            case self::EMBEDDABLE_FIELDS:
                return \__('Embed the value of field into the argument of another field, via notation <code>{{ field }}</code>', 'graphql-api');
            case self::COMPOSABLE_DIRECTIVES:
                return \__('Have directives modify the behavior of other directives', 'graphql-api');
            case self::MUTATIONS:
                return \__('Modify data by executing mutations', 'graphql-api');
            case self::NESTED_MUTATIONS:
                return \__('Execute mutations from any type in the schema, not only from the root', 'graphql-api');
        }
        return parent::getDescription($module);
    }

    public function isEnabledByDefault(string $module): bool
    {
        switch ($module) {
            case self::MULTIPLE_QUERY_EXECUTION:
            case self::REMOVE_IF_NULL_DIRECTIVE:
            case self::EMBEDDABLE_FIELDS:
            case self::COMPOSABLE_DIRECTIVES:
            case self::NESTED_MUTATIONS:
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
            self::NESTED_MUTATIONS => [
                self::OPTION_SCHEME => MutationSchemes::NESTED_WITH_REDUNDANT_ROOT_FIELDS,
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
        if ($module == self::NESTED_MUTATIONS) {
            $option = self::OPTION_SCHEME;
            $moduleSettings[] = [
                Properties::INPUT => $option,
                Properties::NAME => $this->getSettingOptionName(
                    $module,
                    $option
                ),
                Properties::TITLE => \__('Default Mutation Scheme', 'graphql-api'),
                Properties::DESCRIPTION => \__('With nested mutations, a mutation operation in the root type may be considered redundant, so it could be removed from the schema.<br/>For instance, if mutation field <code>Post.update</code> is available, mutation field <code>Root.updatePost</code> could be removed', 'graphql-api'),
                Properties::TYPE => Properties::TYPE_STRING,
                Properties::POSSIBLE_VALUES => [
                    MutationSchemes::STANDARD => \__('Do not enable nested mutations', 'graphql-api'),
                    MutationSchemes::NESTED_WITH_REDUNDANT_ROOT_FIELDS => \__('Enable nested mutations, keeping all mutation fields in the root', 'graphql-api'),
                    MutationSchemes::NESTED_WITHOUT_REDUNDANT_ROOT_FIELDS => \__('Enable nested mutations, removing the redundant mutation fields from the root', 'graphql-api'),
                ],
            ];
        }
        return $moduleSettings;
    }
}
