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

    public const ALL_EXTENSIONS = Plugin::NAMESPACE . '\\bundle-extensions\\all-extensions';

    public const ACCESS_CONTROL = Plugin::NAMESPACE . '\\bundle-extensions\\access-control';
    public const CACHING = Plugin::NAMESPACE . '\\bundle-extensions\\caching';
    public const CUSTOM_ENDPOINTS = Plugin::NAMESPACE . '\\bundle-extensions\\custom-endpoints';
    public const DEPRECATION = Plugin::NAMESPACE . '\\bundle-extensions\\deprecation';
    public const MULTIPLE_QUERY_EXECUTION = Plugin::NAMESPACE . '\\bundle-extensions\\multiple-query-execution';
    public const PERSISTED_QUERIES = Plugin::NAMESPACE . '\\bundle-extensions\\persisted-queries';
    public const POLYLANG_INTEGRATION = Plugin::NAMESPACE . '\\bundle-extensions\\polylang-integration';
    public const QUERY_FUNCTIONS = Plugin::NAMESPACE . '\\bundle-extensions\\query-functions';
    public const SCHEMA_FUNCTIONS = Plugin::NAMESPACE . '\\bundle-extensions\\schema-extensions';

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return array_merge(
            PluginStaticModuleConfiguration::displayGatoGraphQLPROBundleOnExtensionsPage() ? [
                self::PRO,
            ] : [],
            PluginStaticModuleConfiguration::displayGatoGraphQLPROAllExtensionsBundleOnExtensionsPage() ? [
                self::ALL_EXTENSIONS,
            ] : [],
            PluginStaticModuleConfiguration::displayGatoGraphQLPROFeatureBundlesOnExtensionsPage() ? [
                self::ACCESS_CONTROL,
                self::CACHING,
                self::CUSTOM_ENDPOINTS,
                self::DEPRECATION,
                self::MULTIPLE_QUERY_EXECUTION,
                self::PERSISTED_QUERIES,
                self::POLYLANG_INTEGRATION,
                self::QUERY_FUNCTIONS,
                self::SCHEMA_FUNCTIONS,
            ] : [],
        );
    }

    public function getName(string $module): string
    {
        $bundlePlaceholder = \__('“%s” Bundle', 'gatographql');
        $extensionPlaceholder = \__('%s', 'gatographql');
        return match ($module) {
            self::PRO => \__('Gato GraphQL PRO', 'gatographql'),
            self::ALL_EXTENSIONS => sprintf($bundlePlaceholder, \__('All Extensions', 'gatographql')),
            self::ACCESS_CONTROL => sprintf($extensionPlaceholder, \__('Access Control', 'gatographql')),
            self::CACHING => sprintf($extensionPlaceholder, \__('Caching', 'gatographql')),
            self::CUSTOM_ENDPOINTS => sprintf($extensionPlaceholder, \__('Custom Endpoints', 'gatographql')),
            self::DEPRECATION => sprintf($extensionPlaceholder, \__('Deprecation', 'gatographql')),
            self::MULTIPLE_QUERY_EXECUTION => sprintf($extensionPlaceholder, \__('Multiple Query Execution', 'gatographql')),
            self::PERSISTED_QUERIES => sprintf($extensionPlaceholder, \__('Persisted Queries', 'gatographql')),
            self::POLYLANG_INTEGRATION => sprintf($extensionPlaceholder, \__('Polylang Integration', 'gatographql')),
            self::QUERY_FUNCTIONS => sprintf($extensionPlaceholder, \__('Query Functions', 'gatographql')),
            self::SCHEMA_FUNCTIONS => sprintf($extensionPlaceholder, \__('Schema Functions', 'gatographql')),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::PRO => \__('All the PRO extensions for Gato GraphQL, the most powerful GraphQL server for WordPress', 'gatographql'),
            self::ALL_EXTENSIONS => \__('All of Gato GraphQL extensions, in a single plugin', 'gatographql'),
            self::ACCESS_CONTROL => \__('Define Access Control Lists to manage granular access to the API for your users', 'gatographql'),
            self::CACHING => \__('Make your application faster by providing HTTP Caching for the GraphQL response, and by caching the results of expensive operations', 'gatographql'),
            self::CUSTOM_ENDPOINTS => \__('Create custom schemas, with custom access rules for different users, each available under its own endpoint', 'gatographql'),
            self::DEPRECATION => \__('Evolve the GraphQL schema by deprecating fields, and explaining how to replace them, through a user interface', 'gatographql'),
            self::MULTIPLE_QUERY_EXECUTION => \__('Combine multiple GraphQL queries together, and execute them as a single operation, to improve performance and make your queries more manageable', 'gatographql'),
            self::PERSISTED_QUERIES => \__('Use GraphQL queries to create pre-defined endpoints as in REST, obtaining the benefits from both APIs', 'gatographql'),
            self::POLYLANG_INTEGRATION => \__('Integration with the Polylang plugin, providing fields to the GraphQL schema to fetch multilingual data', 'gatographql'),
            self::QUERY_FUNCTIONS => \__('Manipulate the values of fields within the GraphQL query, via a collection of utilities and special directives providing meta-programming capabilities', 'gatographql'),
            self::SCHEMA_FUNCTIONS => \__('Collection of fields and directives added to the GraphQL schema, providing useful functionality concerning sending emails, manipulating strings, connecting to external APIs, and others', 'gatographql'),
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
            self::ALL_EXTENSIONS,
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
            self::ALL_EXTENSIONS => [
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
            self::ACCESS_CONTROL => [
                ExtensionModuleResolver::ACCESS_CONTROL,
                ExtensionModuleResolver::ACCESS_CONTROL_VISITOR_IP,
                ExtensionModuleResolver::SCHEMA_EDITING_ACCESS,
            ],
            self::CACHING => [
                ExtensionModuleResolver::CACHE_CONTROL,
                ExtensionModuleResolver::FIELD_RESOLUTION_CACHING,
            ],
            self::CUSTOM_ENDPOINTS => [
                ExtensionModuleResolver::CUSTOM_ENDPOINTS,
            ],
            self::DEPRECATION => [
                ExtensionModuleResolver::FIELD_DEPRECATION,
                ExtensionModuleResolver::DEPRECATION_NOTIFIER,
            ],
            self::MULTIPLE_QUERY_EXECUTION => [
                ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
            ],
            self::PERSISTED_QUERIES => [
                ExtensionModuleResolver::PERSISTED_QUERIES,
                ExtensionModuleResolver::LOW_LEVEL_PERSISTED_QUERY_EDITING,
            ],
            self::POLYLANG_INTEGRATION => [
                ExtensionModuleResolver::POLYLANG,
            ],
            self::QUERY_FUNCTIONS => [
                ExtensionModuleResolver::FIELD_TO_INPUT,
                ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                ExtensionModuleResolver::FIELD_ON_FIELD,
                ExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
                ExtensionModuleResolver::FIELD_DEFAULT_VALUE,
                ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                ExtensionModuleResolver::RESPONSE_ERROR_TRIGGER,
            ],
            self::SCHEMA_FUNCTIONS => [
                ExtensionModuleResolver::HTTP_CLIENT,
                ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ExtensionModuleResolver::HTTP_REQUEST_VIA_SCHEMA,
                ExtensionModuleResolver::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA,
                ExtensionModuleResolver::EMAIL_SENDER,
                ExtensionModuleResolver::HELPER_FUNCTION_COLLECTION,
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
            self::ALL_EXTENSIONS => [
                self::ACCESS_CONTROL,
                self::CACHING,
                self::CUSTOM_ENDPOINTS,
                self::DEPRECATION,
                self::MULTIPLE_QUERY_EXECUTION,
                self::PERSISTED_QUERIES,
                self::POLYLANG_INTEGRATION,
                self::QUERY_FUNCTIONS,
                self::SCHEMA_FUNCTIONS,
            ],
            default => [],
        };
    }
}
