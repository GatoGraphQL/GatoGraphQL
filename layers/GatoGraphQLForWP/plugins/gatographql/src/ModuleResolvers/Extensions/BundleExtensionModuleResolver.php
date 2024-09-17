<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\PluginStaticModuleConfiguration;

class BundleExtensionModuleResolver extends AbstractBundleExtensionModuleResolver
{
    public const PRO = Plugin::NAMESPACE . '\\bundle-extensions\\pro';
    public const ALL_FEATURE_BUNDLED_EXTENSIONS = Plugin::NAMESPACE . '\\bundle-extensions\\all-feature-bundled-extensions';
    public const CACHING = Plugin::NAMESPACE . '\\bundle-extensions\\caching';
    public const CUSTOM_ENDPOINTS = Plugin::NAMESPACE . '\\bundle-extensions\\custom-endpoints';
    public const DEPRECATION = Plugin::NAMESPACE . '\\bundle-extensions\\deprecation';
    public const MULTIPLE_QUERY_EXECUTION = Plugin::NAMESPACE . '\\bundle-extensions\\multiple-query-execution';
    public const PERSISTED_QUERIES = Plugin::NAMESPACE . '\\bundle-extensions\\persisted-queries';
    public const POLYLANG_INTEGRATION = Plugin::NAMESPACE . '\\bundle-extensions\\polylang-integration';
    public const QUERY_FUNCTIONS = Plugin::NAMESPACE . '\\bundle-extensions\\query-functions';
    public const SECURITY = Plugin::NAMESPACE . '\\bundle-extensions\\security';
    public const SCHEMA_EXTENSIONS = Plugin::NAMESPACE . '\\bundle-extensions\\schema-extensions';

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return array_merge(
            PluginStaticModuleConfiguration::offerGatoGraphQLPROBundle() ? [
                self::PRO,
            ] : [],
            PluginStaticModuleConfiguration::offerGatoGraphQLPROAllFeatureExtensionBundle() ? [
                self::ALL_FEATURE_BUNDLED_EXTENSIONS,
            ] : [],
            PluginStaticModuleConfiguration::offerGatoGraphQLPROFeatureBundles() ? [
                self::CACHING,
                self::CUSTOM_ENDPOINTS,
                self::DEPRECATION,
                self::MULTIPLE_QUERY_EXECUTION,
                self::PERSISTED_QUERIES,
                self::POLYLANG_INTEGRATION,
                self::QUERY_FUNCTIONS,
                self::SECURITY,
                self::SCHEMA_EXTENSIONS,
            ] : [],
        );
    }

    public function getName(string $module): string
    {
        $placeholder = \__('“%s” Bundle', 'gatographql');
        return match ($module) {
            self::PRO => \__('Gato GraphQL PRO', 'gatographql'),
            self::ALL_FEATURE_BUNDLED_EXTENSIONS => sprintf($placeholder, \__('All Extensions', 'gatographql')),
            self::CACHING => sprintf($placeholder, \__('Caching', 'gatographql')),
            self::CUSTOM_ENDPOINTS => sprintf($placeholder, \__('Custom Endpoints', 'gatographql')),
            self::DEPRECATION => sprintf($placeholder, \__('Deprecation', 'gatographql')),
            self::MULTIPLE_QUERY_EXECUTION => sprintf($placeholder, \__('Multiple Query Execution', 'gatographql')),
            self::PERSISTED_QUERIES => sprintf($placeholder, \__('Persisted Queries', 'gatographql')),
            self::POLYLANG_INTEGRATION => sprintf($placeholder, \__('Polylang Integration', 'gatographql')),
            self::QUERY_FUNCTIONS => sprintf($placeholder, \__('Query Functions', 'gatographql')),
            self::SECURITY => sprintf($placeholder, \__('Security', 'gatographql')),
            self::SCHEMA_EXTENSIONS => sprintf($placeholder, \__('Schema Extensions', 'gatographql')),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::PRO => \__('All the PRO extensions for Gato GraphQL, the most powerful GraphQL server for WordPress', 'gatographql'),
            self::ALL_FEATURE_BUNDLED_EXTENSIONS => \__('All of Gato GraphQL extensions, in a single plugin', 'gatographql'),
            self::CACHING => \__('Make your application faster by providing HTTP Caching for the GraphQL response, and by caching the results of expensive operations', 'gatographql'),
            self::CUSTOM_ENDPOINTS => \__('Create custom schemas, with custom access rules for different users, each available under its own endpoint', 'gatographql'),
            self::DEPRECATION => \__('Evolve the GraphQL schema by deprecating fields, and explaining how to replace them, through a user interface', 'gatographql'),
            self::MULTIPLE_QUERY_EXECUTION => \__('Combine multiple GraphQL queries together, and execute them as a single operation, to improve performance and make your queries more manageable', 'gatographql'),
            self::PERSISTED_QUERIES => \__('Use GraphQL queries to create pre-defined endpoints as in REST, obtaining the benefits from both APIs', 'gatographql'),
            self::POLYLANG_INTEGRATION => \__('Integration with the Polylang plugin, providing fields to the GraphQL schema to fetch multilingual data', 'gatographql'),
            self::QUERY_FUNCTIONS => \__('Manipulate the values of fields within the GraphQL query, via a collection of utilities and special directives providing meta-programming capabilities', 'gatographql'),
            self::SECURITY => \__('Grant permission to users to modify the GraphQL schema, and define Access Control Lists to manage granular access to the API based', 'gatographql'),
            self::SCHEMA_EXTENSIONS => \__('Collection of fields and directives added to the GraphQL schema, providing useful functionality concerning sending emails, manipulating strings, connecting to external APIs, and others', 'gatographql'),
            default => parent::getDescription($module),
        };
    }

    public function getPriority(): int
    {
        return 20;
    }

    public function getWebsiteURL(string $module): string
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return match ($module) {
            self::PRO => $moduleConfiguration->getGatoGraphQLWebsiteURL(),
            default => parent::getWebsiteURL($module),
        };
    }

    public function getLogoURL(string $module): string
    {
        if (
            in_array($module, [
            self::PRO,
            self::ALL_FEATURE_BUNDLED_EXTENSIONS,
            ])
        ) {
            return PluginApp::getMainPlugin()->getPluginURL() . 'assets/img/logos/GatoGraphQL-logo-face.png';
        }
        return parent::getLogoURL($module);
    }

    /**
     * @return string[]
     */
    public function getBundledExtensionModules(string $module): array
    {
        return match ($module) {
            self::PRO => [
                ExtensionModuleResolver::ACCESS_CONTROL,
                ExtensionModuleResolver::ACCESS_CONTROL_VISITOR_IP,
                ExtensionModuleResolver::AUTOMATION,
                ExtensionModuleResolver::CACHE_CONTROL,
                ExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
                ExtensionModuleResolver::CUSTOM_ENDPOINTS,
                ExtensionModuleResolver::DEPRECATION_NOTIFIER,
                ExtensionModuleResolver::EMAIL_SENDER,
                ExtensionModuleResolver::EVENTS_MANAGER,
                ExtensionModuleResolver::FIELD_DEFAULT_VALUE,
                ExtensionModuleResolver::FIELD_DEPRECATION,
                ExtensionModuleResolver::FIELD_ON_FIELD,
                ExtensionModuleResolver::FIELD_RESOLUTION_CACHING,
                ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                ExtensionModuleResolver::FIELD_TO_INPUT,
                ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                ExtensionModuleResolver::GOOGLE_TRANSLATE,
                ExtensionModuleResolver::HELPER_FUNCTION_COLLECTION,
                ExtensionModuleResolver::HTTP_CLIENT,
                ExtensionModuleResolver::HTTP_REQUEST_VIA_SCHEMA,
                ExtensionModuleResolver::INTERNAL_GRAPHQL_SERVER,
                ExtensionModuleResolver::LOW_LEVEL_PERSISTED_QUERY_EDITING,
                ExtensionModuleResolver::MULTILINGUALPRESS,
                ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                ExtensionModuleResolver::PERSISTED_QUERIES,
                ExtensionModuleResolver::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA,
                ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ExtensionModuleResolver::POLYLANG,
                ExtensionModuleResolver::RESPONSE_ERROR_TRIGGER,
                ExtensionModuleResolver::SCHEMA_EDITING_ACCESS,
            ],
            self::ALL_FEATURE_BUNDLED_EXTENSIONS => [
                ExtensionModuleResolver::ACCESS_CONTROL,
                ExtensionModuleResolver::ACCESS_CONTROL_VISITOR_IP,
                // ExtensionModuleResolver::AUTOMATION,
                ExtensionModuleResolver::CACHE_CONTROL,
                ExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
                ExtensionModuleResolver::CUSTOM_ENDPOINTS,
                ExtensionModuleResolver::DEPRECATION_NOTIFIER,
                ExtensionModuleResolver::EMAIL_SENDER,
                // ExtensionModuleResolver::EVENTS_MANAGER,
                ExtensionModuleResolver::FIELD_DEFAULT_VALUE,
                ExtensionModuleResolver::FIELD_DEPRECATION,
                ExtensionModuleResolver::FIELD_ON_FIELD,
                ExtensionModuleResolver::FIELD_RESOLUTION_CACHING,
                ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                ExtensionModuleResolver::FIELD_TO_INPUT,
                ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                // ExtensionModuleResolver::GOOGLE_TRANSLATE,
                ExtensionModuleResolver::HELPER_FUNCTION_COLLECTION,
                ExtensionModuleResolver::HTTP_CLIENT,
                ExtensionModuleResolver::HTTP_REQUEST_VIA_SCHEMA,
                // ExtensionModuleResolver::INTERNAL_GRAPHQL_SERVER,
                ExtensionModuleResolver::LOW_LEVEL_PERSISTED_QUERY_EDITING,
                // ExtensionModuleResolver::MULTILINGUALPRESS,
                ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                ExtensionModuleResolver::PERSISTED_QUERIES,
                ExtensionModuleResolver::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA,
                ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ExtensionModuleResolver::POLYLANG,
                ExtensionModuleResolver::RESPONSE_ERROR_TRIGGER,
                ExtensionModuleResolver::SCHEMA_EDITING_ACCESS,
            ],
            self::CACHING => [
                ExtensionModuleResolver::ACCESS_CONTROL,
                ExtensionModuleResolver::ACCESS_CONTROL_VISITOR_IP,
                ExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
                ExtensionModuleResolver::EMAIL_SENDER,
                ExtensionModuleResolver::FIELD_DEFAULT_VALUE,
                ExtensionModuleResolver::FIELD_ON_FIELD,
                ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                ExtensionModuleResolver::FIELD_TO_INPUT,
                ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                ExtensionModuleResolver::HELPER_FUNCTION_COLLECTION,
                ExtensionModuleResolver::HTTP_CLIENT,
                ExtensionModuleResolver::HTTP_REQUEST_VIA_SCHEMA,
                ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                ExtensionModuleResolver::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA,
                ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ExtensionModuleResolver::RESPONSE_ERROR_TRIGGER,
            ],
            self::CUSTOM_ENDPOINTS => [
                ExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
                ExtensionModuleResolver::FIELD_DEFAULT_VALUE,
                ExtensionModuleResolver::FIELD_ON_FIELD,
                ExtensionModuleResolver::FIELD_TO_INPUT,
                ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                ExtensionModuleResolver::HELPER_FUNCTION_COLLECTION,
                ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
            ],
            self::DEPRECATION => [
                ExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
                ExtensionModuleResolver::EMAIL_SENDER,
                ExtensionModuleResolver::FIELD_DEFAULT_VALUE,
                ExtensionModuleResolver::FIELD_ON_FIELD,
                ExtensionModuleResolver::FIELD_RESOLUTION_CACHING,
                ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                ExtensionModuleResolver::FIELD_TO_INPUT,
                ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                ExtensionModuleResolver::HELPER_FUNCTION_COLLECTION,
                ExtensionModuleResolver::HTTP_CLIENT,
                ExtensionModuleResolver::HTTP_REQUEST_VIA_SCHEMA,
                ExtensionModuleResolver::INTERNAL_GRAPHQL_SERVER,
                ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                ExtensionModuleResolver::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA,
                ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ExtensionModuleResolver::RESPONSE_ERROR_TRIGGER,
            ],
            self::MULTIPLE_QUERY_EXECUTION => [
                ExtensionModuleResolver::ACCESS_CONTROL,
                ExtensionModuleResolver::ACCESS_CONTROL_VISITOR_IP,
                ExtensionModuleResolver::CACHE_CONTROL,
                ExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
                ExtensionModuleResolver::DEPRECATION_NOTIFIER,
                ExtensionModuleResolver::FIELD_DEFAULT_VALUE,
                ExtensionModuleResolver::FIELD_DEPRECATION,
                ExtensionModuleResolver::FIELD_TO_INPUT,
                ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                ExtensionModuleResolver::LOW_LEVEL_PERSISTED_QUERY_EDITING,
                ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                ExtensionModuleResolver::RESPONSE_ERROR_TRIGGER,
                ExtensionModuleResolver::SCHEMA_EDITING_ACCESS,
            ],
            self::PERSISTED_QUERIES => [
                ExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
                ExtensionModuleResolver::FIELD_DEFAULT_VALUE,
                ExtensionModuleResolver::FIELD_ON_FIELD,
                ExtensionModuleResolver::FIELD_RESOLUTION_CACHING,
                ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                ExtensionModuleResolver::FIELD_TO_INPUT,
                ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                ExtensionModuleResolver::HELPER_FUNCTION_COLLECTION,
                ExtensionModuleResolver::HTTP_CLIENT,
                ExtensionModuleResolver::HTTP_REQUEST_VIA_SCHEMA,
                ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                ExtensionModuleResolver::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA,
                ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ExtensionModuleResolver::RESPONSE_ERROR_TRIGGER,
            ],
            self::POLYLANG_INTEGRATION => [
                ExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
                ExtensionModuleResolver::FIELD_ON_FIELD,
                ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                ExtensionModuleResolver::FIELD_TO_INPUT,
                ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                ExtensionModuleResolver::GOOGLE_TRANSLATE,
                ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
            ],
            self::QUERY_FUNCTIONS => [
                ExtensionModuleResolver::AUTOMATION,
                ExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
                ExtensionModuleResolver::EMAIL_SENDER,
                ExtensionModuleResolver::FIELD_DEFAULT_VALUE,
                ExtensionModuleResolver::FIELD_ON_FIELD,
                ExtensionModuleResolver::FIELD_RESOLUTION_CACHING,
                ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                ExtensionModuleResolver::FIELD_TO_INPUT,
                ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                ExtensionModuleResolver::HELPER_FUNCTION_COLLECTION,
                ExtensionModuleResolver::HTTP_CLIENT,
                ExtensionModuleResolver::HTTP_REQUEST_VIA_SCHEMA,
                ExtensionModuleResolver::INTERNAL_GRAPHQL_SERVER,
                ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                ExtensionModuleResolver::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA,
                ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ExtensionModuleResolver::RESPONSE_ERROR_TRIGGER,
            ],
            self::SECURITY => [
                ExtensionModuleResolver::EMAIL_SENDER,
                ExtensionModuleResolver::FIELD_ON_FIELD,
                ExtensionModuleResolver::FIELD_TO_INPUT,
                ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                ExtensionModuleResolver::HELPER_FUNCTION_COLLECTION,
                ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                ExtensionModuleResolver::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA,
                ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
            ],
            self::SCHEMA_EXTENSIONS => [
                ExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
                ExtensionModuleResolver::FIELD_DEFAULT_VALUE,
                ExtensionModuleResolver::FIELD_ON_FIELD,
                ExtensionModuleResolver::FIELD_RESOLUTION_CACHING,
                ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                ExtensionModuleResolver::FIELD_TO_INPUT,
                ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                ExtensionModuleResolver::HELPER_FUNCTION_COLLECTION,
                ExtensionModuleResolver::HTTP_CLIENT,
                ExtensionModuleResolver::HTTP_REQUEST_VIA_SCHEMA,
                ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                ExtensionModuleResolver::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA,
                ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ExtensionModuleResolver::RESPONSE_ERROR_TRIGGER,
            ],
            default => [],
        };
    }

    /**
     * @return string[]
     */
    public function getBundledBundleExtensionModules(string $module): array
    {
        return match ($module) {
            // "Gato GraphQL PRO" bundles all other bundles
            self::PRO => array_diff(
                $this->getModulesToResolve(),
                [$module]
            ),
            self::ALL_FEATURE_BUNDLED_EXTENSIONS => [
                self::CACHING,
                self::CUSTOM_ENDPOINTS,
                self::DEPRECATION,
                self::MULTIPLE_QUERY_EXECUTION,
                self::PERSISTED_QUERIES,
                self::POLYLANG_INTEGRATION,
                self::QUERY_FUNCTIONS,
                self::SECURITY,
                self::SCHEMA_EXTENSIONS,
            ],
            self::DEPRECATION => [
                self::PERSISTED_QUERIES,
                self::CUSTOM_ENDPOINTS,
                self::SECURITY,
                self::SCHEMA_EXTENSIONS,
            ],
            self::CACHING => [
                self::CUSTOM_ENDPOINTS,
                self::SECURITY,
                self::SCHEMA_EXTENSIONS,
            ],
            self::QUERY_FUNCTIONS => [
                self::DEPRECATION,
                self::PERSISTED_QUERIES,
                self::CUSTOM_ENDPOINTS,
                self::SECURITY,
                self::SCHEMA_EXTENSIONS,
            ],
            self::PERSISTED_QUERIES => [
                self::CUSTOM_ENDPOINTS,
            ],
            self::SCHEMA_EXTENSIONS => [
                self::PERSISTED_QUERIES,
                self::CUSTOM_ENDPOINTS,
            ],
            default => [],
        };
    }
}
