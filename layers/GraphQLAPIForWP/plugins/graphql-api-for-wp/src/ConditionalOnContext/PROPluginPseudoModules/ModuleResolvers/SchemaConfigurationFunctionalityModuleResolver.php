<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\PROPluginPseudoModules\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;
use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface;
use GraphQLAPI\GraphQLAPI\Module;
use GraphQLAPI\GraphQLAPI\ModuleConfiguration;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\AbstractFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver as UpstreamSchemaConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolverTrait;
use GraphQLAPI\GraphQLAPI\ModuleSettings\Properties;
use GraphQLAPI\GraphQLAPIPRO\Constants\GlobalFieldsSchemaExposure;
use PoP\AccessControl\Schema\SchemaModes;
use PoP\ComponentModel\App;

class SchemaConfigurationFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use ModuleResolverTrait;
    use SchemaConfigurationFunctionalityModuleResolverTrait;

    public final const PUBLIC_PRIVATE_SCHEMA = 'placeholder:' . Plugin::NAMESPACE . '\public-private-schema';
    public final const GLOBAL_FIELDS = 'placeholder:' . Plugin::NAMESPACE . '\global-fields';
    public final const FIELD_TO_INPUT = 'placeholder:' . Plugin::NAMESPACE . '\field-to-input';
    public final const COMPOSABLE_DIRECTIVES = 'placeholder:' . Plugin::NAMESPACE . '\composable-directives';
    public final const MULTIFIELD_DIRECTIVES = 'placeholder:' . Plugin::NAMESPACE . '\multifield-directives';
    public final const MULTIPLE_QUERY_EXECUTION = 'placeholder:' . Plugin::NAMESPACE . '\multiple-query-execution';
    public final const DEPRECATION_NOTIFIER = 'placeholder:' . Plugin::NAMESPACE . '\deprecation-notifier';

    // /**
    //  * Setting options
    //  */
    // public final const OPTION_MODE = 'mode';
    // public final const OPTION_ENABLE_GRANULAR = 'granular';
    // public final const DEFAULT_SCHEMA_EXPOSURE = 'default-schema-exposure';
    // public final const SCHEMA_EXPOSURE_FOR_ADMIN_CLIENTS = 'schema-exposure-for-admin-clients';

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
            self::GLOBAL_FIELDS,
            self::PUBLIC_PRIVATE_SCHEMA,
            self::MULTIPLE_QUERY_EXECUTION,
            self::FIELD_TO_INPUT,
            self::COMPOSABLE_DIRECTIVES,
            self::MULTIFIELD_DIRECTIVES,
            self::DEPRECATION_NOTIFIER,
        ];
    }

    /**
     * @return array<string[]> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     */
    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
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
        return match ($module) {
            self::PUBLIC_PRIVATE_SCHEMA => \__('Public/Private Schema [PRO]', 'graphql-api-pro'),
            self::GLOBAL_FIELDS => \__('Global Fields [PRO]', 'graphql-api-pro'),
            self::FIELD_TO_INPUT => \__('Field to Input [PRO]', 'graphql-api-pro'),
            self::COMPOSABLE_DIRECTIVES => \__('Composable Directives [PRO]', 'graphql-api-pro'),
            self::MULTIFIELD_DIRECTIVES => \__('Multi-Field Directives [PRO]', 'graphql-api-pro'),
            self::MULTIPLE_QUERY_EXECUTION => \__('Multiple Query Execution [PRO]', 'graphql-api-pro'),
            self::DEPRECATION_NOTIFIER => \__('Deprecation Notifier [PRO]', 'graphql-api-pro'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::PUBLIC_PRIVATE_SCHEMA => \__('Enable to communicate the existence of some field from the schema to certain users only (private mode) or to everyone (public mode). If disabled, fields are always available to everyone (public mode)', 'graphql-api-pro'),
            self::GLOBAL_FIELDS => \__('Fields added to all types in the schema, generally for executing functionality (not retrieving data)', 'graphql-api-pro'),
            self::FIELD_TO_INPUT => \__('Retrieve the value of a field, manipulate it, and input it into another field, all within the same query', 'graphql-api-pro'),
            self::COMPOSABLE_DIRECTIVES => \__('Have directives modify the behavior of other directives', 'graphql-api-pro'),
            self::MULTIFIELD_DIRECTIVES => \__('A single directive can be applied to multiple fields, for performance and extended use cases', 'graphql-api-pro'),
            self::MULTIPLE_QUERY_EXECUTION => \__('Execute multiple GraphQL queries in a single operation', 'graphql-api-pro'),
            self::DEPRECATION_NOTIFIER => \__('Send deprecations in the response to the query (and not only when doing introspection), under the top-level entry <code>extensions</code>', 'graphql-api-pro'),
            default => parent::getDescription($module),
        };
    }

    public function canBeDisabled(string $module): bool
    {
        return match ($module) {
            self::GLOBAL_FIELDS => false,
            default => parent::canBeDisabled($module),
        };
    }

    // /**
    //  * Default value for an option set by the module
    //  */
    // public function getSettingsDefaultValue(string $module, string $option): mixed
    // {
    //     $defaultValues = [
    //         self::PUBLIC_PRIVATE_SCHEMA => [
    //             self::OPTION_MODE => SchemaModes::PUBLIC_SCHEMA_MODE,
    //             self::OPTION_ENABLE_GRANULAR => true,
    //         ],
    //         self::GLOBAL_FIELDS => [
    //             self::DEFAULT_SCHEMA_EXPOSURE => GlobalFieldsSchemaExposure::EXPOSE_IN_ROOT_TYPE_ONLY,
    //             self::SCHEMA_EXPOSURE_FOR_ADMIN_CLIENTS => GlobalFieldsSchemaExposure::EXPOSE_IN_ROOT_TYPE_ONLY,
    //         ],
    //         self::FIELD_TO_INPUT => [
    //             ModuleSettingOptions::DEFAULT_VALUE => false,
    //             ModuleSettingOptions::VALUE_FOR_ADMIN_CLIENTS => true,
    //         ],
    //         self::MULTIFIELD_DIRECTIVES => [
    //             ModuleSettingOptions::DEFAULT_VALUE => false,
    //             ModuleSettingOptions::VALUE_FOR_ADMIN_CLIENTS => true,
    //         ],
    //         self::COMPOSABLE_DIRECTIVES => [
    //             ModuleSettingOptions::DEFAULT_VALUE => false,
    //             ModuleSettingOptions::VALUE_FOR_ADMIN_CLIENTS => true,
    //         ],
    //         self::MULTIPLE_QUERY_EXECUTION => [
    //             ModuleSettingOptions::DEFAULT_VALUE => false,
    //             ModuleSettingOptions::VALUE_FOR_ADMIN_CLIENTS => true,
    //         ],
    //     ];
    //     return $defaultValues[$module][$option] ?? null;
    // }

    // /**
    //  * Array with the inputs to show as settings for the module
    //  *
    //  * @return array<array<string,mixed>> List of settings for the module, each entry is an array with property => value
    //  */
    // public function getSettings(string $module): array
    // {
    //     $moduleSettings = parent::getSettings($module);
    //     $defaultValueLabel = $this->getDefaultValueLabel();
    //     $defaultValueDesc = $this->getDefaultValueDescription();
    //     $adminClientsDesc = $this->getAdminClientDescription();
    //     if ($module === self::PUBLIC_PRIVATE_SCHEMA) {
    //         /** @var ModuleConfiguration */
    //         $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
    //         $whereModules = [
    //             UpstreamSchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION,
    //             AccessControlFunctionalityModuleResolver::ACCESS_CONTROL,
    //         ];
    //         $whereModuleNames = array_map(
    //             fn ($whereModule) => '▹ ' . $this->getModuleRegistry()->getModuleResolver($whereModule)->getName($whereModule),
    //             $whereModules
    //         );
    //         $option = self::OPTION_MODE;
    //         $moduleSettings[] = [
    //             Properties::INPUT => $option,
    //             Properties::NAME => $this->getSettingOptionName(
    //                 $module,
    //                 $option
    //             ),
    //             Properties::TITLE => \__('Default visibility', 'graphql-api-pro'),
    //             Properties::DESCRIPTION => sprintf(
    //                 \__('Visibility to use for fields and directives in the schema when option <code>"%s"</code> is selected (in %s)', 'graphql-api-pro'),
    //                 $moduleConfiguration->getSettingsValueLabel(),
    //                 implode(
    //                     \__(', ', 'graphql-api-pro'),
    //                     $whereModuleNames
    //                 )
    //             ),
    //             Properties::TYPE => Properties::TYPE_STRING,
    //             Properties::POSSIBLE_VALUES => [
    //                 SchemaModes::PUBLIC_SCHEMA_MODE => \__('Public', 'graphql-api-pro'),
    //                 SchemaModes::PRIVATE_SCHEMA_MODE => \__('Private', 'graphql-api-pro'),
    //             ],
    //         ];
    //         $option = self::OPTION_ENABLE_GRANULAR;
    //         $moduleSettings[] = [
    //             Properties::INPUT => $option,
    //             Properties::NAME => $this->getSettingOptionName(
    //                 $module,
    //                 $option
    //             ),
    //             Properties::TITLE => \__('Enable granular control?', 'graphql-api-pro'),
    //             Properties::DESCRIPTION => \__('Enable to select the visibility for a set of fields/directives when editing the Access Control List', 'graphql-api-pro'),
    //             Properties::TYPE => Properties::TYPE_BOOL,
    //         ];
    //     } elseif ($module === self::GLOBAL_FIELDS) {
    //         $globalFieldsSchemaExposureValues = [
    //             GlobalFieldsSchemaExposure::DO_NOT_EXPOSE => \__('Do not expose', 'graphql-api-pro'),
    //             GlobalFieldsSchemaExposure::EXPOSE_IN_ROOT_TYPE_ONLY => \__('Expose under the Root type only', 'graphql-api-pro'),
    //             GlobalFieldsSchemaExposure::EXPOSE_IN_ALL_TYPES => \__('Expose under all types', 'graphql-api-pro'),
    //         ];
    //         $option = self::DEFAULT_SCHEMA_EXPOSURE;
    //         $moduleSettings[] = [
    //             Properties::INPUT => $option,
    //             Properties::NAME => $this->getSettingOptionName(
    //                 $module,
    //                 $option
    //             ),
    //             Properties::TITLE => sprintf(
    //                 \__('Schema exposure. %s', 'graphql-api-pro'),
    //                 $defaultValueLabel
    //             ),
    //             Properties::DESCRIPTION => sprintf(
    //                 \__('Under what types to expose global fields. %s', 'graphql-api-pro'),
    //                 $defaultValueDesc
    //             ),
    //             Properties::TYPE => Properties::TYPE_STRING,
    //             Properties::POSSIBLE_VALUES => $globalFieldsSchemaExposureValues,
    //         ];
    //         $option = self::SCHEMA_EXPOSURE_FOR_ADMIN_CLIENTS;
    //         $moduleSettings[] = [
    //             Properties::INPUT => $option,
    //             Properties::NAME => $this->getSettingOptionName(
    //                 $module,
    //                 $option
    //             ),
    //             Properties::TITLE => \__('Schema exposure for the Admin', 'graphql-api-pro'),
    //             Properties::DESCRIPTION => sprintf(
    //                 \__('Under what types to expose global fields in the wp-admin. %s', 'graphql-api-pro'),
    //                 $adminClientsDesc
    //             ),
    //             Properties::TYPE => Properties::TYPE_STRING,
    //             Properties::POSSIBLE_VALUES => $globalFieldsSchemaExposureValues,
    //         ];
    //     } elseif ($module === self::FIELD_TO_INPUT) {
    //         $option = ModuleSettingOptions::DEFAULT_VALUE;
    //         $moduleSettings[] = [
    //             Properties::INPUT => $option,
    //             Properties::NAME => $this->getSettingOptionName(
    //                 $module,
    //                 $option
    //             ),
    //             Properties::TITLE => sprintf(
    //                 \__('Enable field to input? %s', 'graphql-api-pro'),
    //                 $defaultValueLabel
    //             ),
    //             Properties::DESCRIPTION => sprintf(
    //                 \__('Enable the "field to input" feature in the schema. %s', 'graphql-api-pro'),
    //                 $defaultValueDesc
    //             ),
    //             Properties::TYPE => Properties::TYPE_BOOL,
    //         ];
    //         $option = ModuleSettingOptions::VALUE_FOR_ADMIN_CLIENTS;
    //         $moduleSettings[] = [
    //             Properties::INPUT => $option,
    //             Properties::NAME => $this->getSettingOptionName(
    //                 $module,
    //                 $option
    //             ),
    //             Properties::TITLE => \__('Enable field to input for the Admin?', 'graphql-api-pro'),
    //             Properties::DESCRIPTION => sprintf(
    //                 \__('Enable field to input in the wp-admin? %s', 'graphql-api-pro'),
    //                 $adminClientsDesc
    //             ),
    //             Properties::TYPE => Properties::TYPE_BOOL,
    //         ];
    //     } elseif ($module === self::MULTIFIELD_DIRECTIVES) {
    //         $option = ModuleSettingOptions::DEFAULT_VALUE;
    //         $moduleSettings[] = [
    //             Properties::INPUT => $option,
    //             Properties::NAME => $this->getSettingOptionName(
    //                 $module,
    //                 $option
    //             ),
    //             Properties::TITLE => sprintf(
    //                 \__('Enable multi-field directives? %s', 'graphql-api-pro'),
    //                 $defaultValueLabel
    //             ),
    //             Properties::DESCRIPTION => sprintf(
    //                 \__('Have a single directive be applied to multiple fields. %s', 'graphql-api-pro'),
    //                 $defaultValueDesc
    //             ),
    //             Properties::TYPE => Properties::TYPE_BOOL,
    //         ];
    //         $option = ModuleSettingOptions::VALUE_FOR_ADMIN_CLIENTS;
    //         $moduleSettings[] = [
    //             Properties::INPUT => $option,
    //             Properties::NAME => $this->getSettingOptionName(
    //                 $module,
    //                 $option
    //             ),
    //             Properties::TITLE => \__('Enable multi-field directives for the Admin?', 'graphql-api-pro'),
    //             Properties::DESCRIPTION => sprintf(
    //                 \__('Enable multi-field directives in the wp-admin? %s', 'graphql-api-pro'),
    //                 $adminClientsDesc
    //             ),
    //             Properties::TYPE => Properties::TYPE_BOOL,
    //         ];
    //     } elseif ($module === self::COMPOSABLE_DIRECTIVES) {
    //         $option = ModuleSettingOptions::DEFAULT_VALUE;
    //         $moduleSettings[] = [
    //             Properties::INPUT => $option,
    //             Properties::NAME => $this->getSettingOptionName(
    //                 $module,
    //                 $option
    //             ),
    //             Properties::TITLE => sprintf(
    //                 \__('Enable composable directives? %s', 'graphql-api-pro'),
    //                 $defaultValueLabel
    //             ),
    //             Properties::DESCRIPTION => sprintf(
    //                 \__('Enable adding composable directives (also called "meta-directives", such as <code>@forEach</code>, <code>@underArrayItem</code> and <code>@underJSONObjectProperty</code>) to the schema. %s', 'graphql-api-pro'),
    //                 $defaultValueDesc
    //             ),
    //             Properties::TYPE => Properties::TYPE_BOOL,
    //         ];
    //         $option = ModuleSettingOptions::VALUE_FOR_ADMIN_CLIENTS;
    //         $moduleSettings[] = [
    //             Properties::INPUT => $option,
    //             Properties::NAME => $this->getSettingOptionName(
    //                 $module,
    //                 $option
    //             ),
    //             Properties::TITLE => \__('Enable composable directives for the Admin?', 'graphql-api-pro'),
    //             Properties::DESCRIPTION => sprintf(
    //                 \__('Enable composable directives in the wp-admin? %s', 'graphql-api-pro'),
    //                 $adminClientsDesc
    //             ),
    //             Properties::TYPE => Properties::TYPE_BOOL,
    //         ];
    //     } elseif ($module === self::MULTIPLE_QUERY_EXECUTION) {
    //         $option = ModuleSettingOptions::DEFAULT_VALUE;
    //         $moduleSettings[] = [
    //             Properties::INPUT => $option,
    //             Properties::NAME => $this->getSettingOptionName(
    //                 $module,
    //                 $option
    //             ),
    //             Properties::TITLE => sprintf(
    //                 \__('Enable multiple query execution? %s', 'graphql-api-pro'),
    //                 $defaultValueLabel
    //             ),
    //             Properties::DESCRIPTION => sprintf(
    //                 \__('Execute multiple queries in a single request, sharing data among them via <code>@export</code>. %s', 'graphql-api-pro'),
    //                 $defaultValueDesc
    //             ),
    //             Properties::TYPE => Properties::TYPE_BOOL,
    //         ];
    //         $option = ModuleSettingOptions::VALUE_FOR_ADMIN_CLIENTS;
    //         $moduleSettings[] = [
    //             Properties::INPUT => $option,
    //             Properties::NAME => $this->getSettingOptionName(
    //                 $module,
    //                 $option
    //             ),
    //             Properties::TITLE => \__('Enable multiple query execution for the Admin?', 'graphql-api-pro'),
    //             Properties::DESCRIPTION => sprintf(
    //                 \__('Enable multiple query execution in the wp-admin? %s', 'graphql-api-pro'),
    //                 $adminClientsDesc
    //             ),
    //             Properties::TYPE => Properties::TYPE_BOOL,
    //         ];
    //     }

    //     return $moduleSettings;
    // }
}
