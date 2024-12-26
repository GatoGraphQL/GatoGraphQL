<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\PluginStaticModuleConfiguration;

class PowerBundleExtensionModuleResolver extends AbstractBundleExtensionModuleResolver
{
    // public const PRO = Plugin::NAMESPACE . '\\bundle-extensions\\pro';

    // public const ALL_EXTENSIONS = Plugin::NAMESPACE . '\\bundle-extensions\\all-extensions';
    public const POWER_EXTENSIONS = Plugin::NAMESPACE . '\\bundle-extensions\\power-extensions';

    public const ACCESS_CONTROL = Plugin::NAMESPACE . '\\bundle-extensions\\access-control';
    public const CACHING = Plugin::NAMESPACE . '\\bundle-extensions\\caching';
    public const CUSTOM_ENDPOINTS = Plugin::NAMESPACE . '\\bundle-extensions\\custom-endpoints';
    public const DEPRECATION = Plugin::NAMESPACE . '\\bundle-extensions\\deprecation';
    public const HTTP_CLIENT = Plugin::NAMESPACE . '\\bundle-extensions\\http-client';
    public const INTERNAL_GRAPHQL_SERVER = Plugin::NAMESPACE . '\\bundle-extensions\\internal-graphql-server';
    public const MULTIPLE_QUERY_EXECUTION = Plugin::NAMESPACE . '\\bundle-extensions\\multiple-query-execution';
    public const PERSISTED_QUERIES = Plugin::NAMESPACE . '\\bundle-extensions\\persisted-queries';
    public const QUERY_FUNCTIONS = Plugin::NAMESPACE . '\\bundle-extensions\\query-functions';
    public const SCHEMA_FUNCTIONS = Plugin::NAMESPACE . '\\bundle-extensions\\schema-functions';

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return array_merge(
            // PluginStaticModuleConfiguration::displayGatoGraphQLPROBundleOnExtensionsPage() ? [
            //     self::PRO,
            // ] : [],
            PluginStaticModuleConfiguration::displayGatoGraphQLPROAllExtensionsBundleOnExtensionsPage() ? [
                // // self::ALL_EXTENSIONS,
                self::POWER_EXTENSIONS,
            ] : [],
            PluginStaticModuleConfiguration::displayGatoGraphQLPROFeatureBundlesOnExtensionsPage() ? [
                self::ACCESS_CONTROL,
                self::CACHING,
                self::CUSTOM_ENDPOINTS,
                self::DEPRECATION,
                self::HTTP_CLIENT,
                self::INTERNAL_GRAPHQL_SERVER,
                self::MULTIPLE_QUERY_EXECUTION,
                self::PERSISTED_QUERIES,
                self::QUERY_FUNCTIONS,
                self::SCHEMA_FUNCTIONS,
            ] : [],
        );
    }

    public function getName(string $module): string
    {
        $bundlePlaceholder = \__('“%s” bundle', 'gatographql');
        $extensionPlaceholder = \__('%s', 'gatographql');
        return match ($module) {
            // self::PRO => \__('Gato GraphQL PRO', 'gatographql'),
            // self::ALL_EXTENSIONS => sprintf($bundlePlaceholder, \__('All Extensions', 'gatographql')),
            self::POWER_EXTENSIONS => sprintf($bundlePlaceholder, \__('Power Extensions', 'gatographql')),

            self::ACCESS_CONTROL => sprintf($extensionPlaceholder, \__('Access Control', 'gatographql')),
            self::CACHING => sprintf($extensionPlaceholder, \__('Caching', 'gatographql')),
            self::CUSTOM_ENDPOINTS => sprintf($extensionPlaceholder, \__('Custom Endpoints', 'gatographql')),
            self::DEPRECATION => sprintf($extensionPlaceholder, \__('Deprecation', 'gatographql')),
            self::HTTP_CLIENT => sprintf($extensionPlaceholder, \__('HTTP Client', 'gatographql')),
            self::INTERNAL_GRAPHQL_SERVER => sprintf($extensionPlaceholder, \__('Internal GraphQL Server', 'gatographql')),
            self::MULTIPLE_QUERY_EXECUTION => sprintf($extensionPlaceholder, \__('Multiple Query Execution', 'gatographql')),
            self::PERSISTED_QUERIES => sprintf($extensionPlaceholder, \__('Persisted Queries', 'gatographql')),
            self::QUERY_FUNCTIONS => sprintf($extensionPlaceholder, \__('Query Functions', 'gatographql')),
            self::SCHEMA_FUNCTIONS => sprintf($extensionPlaceholder, \__('Schema Functions', 'gatographql')),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            // self::PRO => \__('All the PRO extensions for Gato GraphQL, the most powerful GraphQL server for WordPress', 'gatographql'),
            // self::ALL_EXTENSIONS => \__('All of Gato GraphQL extensions, in a single plugin', 'gatographql'),
            self::POWER_EXTENSIONS => \__('All of Gato GraphQL\'s power extensions, in a single plugin', 'gatographql'),

            self::ACCESS_CONTROL => \__('Define Access Control Lists to manage granular access to the API for your users', 'gatographql'),
            self::CACHING => \__('Make your application faster by providing HTTP Caching for the GraphQL response, and by caching the results of expensive operations', 'gatographql'),
            self::CUSTOM_ENDPOINTS => \__('Create custom schemas, with custom access rules for different users, each available under its own endpoint', 'gatographql'),
            self::DEPRECATION => \__('Evolve the GraphQL schema by deprecating fields, and explaining how to replace them, through a user interface', 'gatographql'),
            self::HTTP_CLIENT => \__('Connect to and interact with external services via their APIs', 'gatographql'),
            self::INTERNAL_GRAPHQL_SERVER => \__('Execute GraphQL queries directly within your application, using PHP code', 'gatographql'),
            self::MULTIPLE_QUERY_EXECUTION => \__('Combine multiple GraphQL queries together, and execute them as a single operation, to improve performance and make your queries more manageable', 'gatographql'),
            self::PERSISTED_QUERIES => \__('Use GraphQL queries to create pre-defined endpoints as in REST, obtaining the benefits from both APIs', 'gatographql'),
            self::QUERY_FUNCTIONS => \__('Manipulate the values of fields within the GraphQL query, via a collection of utilities and special directives providing meta-programming capabilities', 'gatographql'),
            self::SCHEMA_FUNCTIONS => \__('Collection of fields and directives added to the GraphQL schema, providing useful functionality', 'gatographql'),
            default => parent::getDescription($module),
        };
    }

    public function getPriority(): int
    {
        return 30;
    }

    public function getWebsiteURL(string $module): string
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return match ($module) {
            // self::PRO,
            // self::ALL_EXTENSIONS,
            self::POWER_EXTENSIONS =>
                $moduleConfiguration->getGatoGraphQLWebsiteURL(),
            default
                => parent::getWebsiteURL($module),
        };
    }

    public function getLogoURL(string $module): string
    {
        $pluginURL = PluginApp::getMainPlugin()->getPluginURL();
        return match ($module) {
            // self::PRO,
            // self::ALL_EXTENSIONS,
            self::POWER_EXTENSIONS
                => $pluginURL . 'assets/img/logos/GatoGraphQL-logo-face.png',
            self::ACCESS_CONTROL
                => $pluginURL . 'assets/img/extension-logos/access-control.svg',
            self::CACHING
                => $pluginURL . 'assets/img/extension-logos/caching.svg',
            self::CUSTOM_ENDPOINTS
                => $pluginURL . 'assets/img/extension-logos/custom-endpoints.svg',
            self::DEPRECATION
                => $pluginURL . 'assets/img/extension-logos/deprecation.svg',
            self::HTTP_CLIENT
                => $pluginURL . 'assets/img/extension-logos/http-client.svg',
            self::INTERNAL_GRAPHQL_SERVER
                => $pluginURL . 'assets/img/extension-logos/internal-graphql-server.svg',
            self::MULTIPLE_QUERY_EXECUTION
                => $pluginURL . 'assets/img/extension-logos/multiple-query-execution.svg',
            self::PERSISTED_QUERIES
                => $pluginURL . 'assets/img/extension-logos/persisted-queries.svg',
            self::QUERY_FUNCTIONS
                => $pluginURL . 'assets/img/extension-logos/query-functions.svg',
            self::SCHEMA_FUNCTIONS
                => $pluginURL . 'assets/img/extension-logos/schema-functions.svg',
            default => parent::getLogoURL($module),
        };
    }

    /**
     * @return string[]
     */
    public function getBundledExtensionModules(string $module): array
    {
        return match ($module) {
            // self::PRO => [
            //     PowerExtensionModuleResolver::ACCESS_CONTROL,
            //     PowerExtensionModuleResolver::ACCESS_CONTROL_VISITOR_IP,
            //     PremiumExtensionModuleResolver::AUTOMATION,
            //     PowerExtensionModuleResolver::CACHE_CONTROL,
            //     PowerExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
            //     PowerExtensionModuleResolver::CUSTOM_ENDPOINTS,
            //     PowerExtensionModuleResolver::DEPRECATION_NOTIFIER,
            //     PowerExtensionModuleResolver::EMAIL_SENDER,
            //     PremiumExtensionModuleResolver::EVENTS_MANAGER,
            //     PowerExtensionModuleResolver::FIELD_DEFAULT_VALUE,
            //     PowerExtensionModuleResolver::FIELD_DEPRECATION,
            //     PowerExtensionModuleResolver::FIELD_ON_FIELD,
            //     PowerExtensionModuleResolver::FIELD_RESOLUTION_CACHING,
            //     PowerExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
            //     PowerExtensionModuleResolver::FIELD_TO_INPUT,
            //     PowerExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
            //     PremiumExtensionModuleResolver::GOOGLE_TRANSLATE,
            //     PowerExtensionModuleResolver::HELPER_FUNCTION_COLLECTION,
            //     PowerExtensionModuleResolver::HTTP_CLIENT,
            //     PowerExtensionModuleResolver::HTTP_REQUEST_VIA_SCHEMA,
            //     PowerExtensionModuleResolver::INTERNAL_GRAPHQL_SERVER,
            //     PowerExtensionModuleResolver::LOW_LEVEL_PERSISTED_QUERY_EDITING,
            //     PremiumExtensionModuleResolver::MULTILINGUALPRESS,
            //     PowerExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
            //     PowerExtensionModuleResolver::PERSISTED_QUERIES,
            //     PowerExtensionModuleResolver::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA,
            //     PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
            //     PremiumExtensionModuleResolver::POLYLANG,
            //     PowerExtensionModuleResolver::RESPONSE_ERROR_TRIGGER,
            // ],
            // self::ALL_EXTENSIONS => [
            //     PowerExtensionModuleResolver::ACCESS_CONTROL,
            //     PowerExtensionModuleResolver::ACCESS_CONTROL_VISITOR_IP,
            //     PowerExtensionModuleResolver::CACHE_CONTROL,
            //     PowerExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
            //     PowerExtensionModuleResolver::CUSTOM_ENDPOINTS,
            //     PowerExtensionModuleResolver::DEPRECATION_NOTIFIER,
            //     PowerExtensionModuleResolver::EMAIL_SENDER,
            //     PowerExtensionModuleResolver::FIELD_DEFAULT_VALUE,
            //     PowerExtensionModuleResolver::FIELD_DEPRECATION,
            //     PowerExtensionModuleResolver::FIELD_ON_FIELD,
            //     PowerExtensionModuleResolver::FIELD_RESOLUTION_CACHING,
            //     PowerExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
            //     PowerExtensionModuleResolver::FIELD_TO_INPUT,
            //     PowerExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
            //     PowerExtensionModuleResolver::HELPER_FUNCTION_COLLECTION,
            //     PowerExtensionModuleResolver::HTTP_CLIENT,
            //     PowerExtensionModuleResolver::HTTP_REQUEST_VIA_SCHEMA,
            //     PowerExtensionModuleResolver::INTERNAL_GRAPHQL_SERVER,
            //     PowerExtensionModuleResolver::LOW_LEVEL_PERSISTED_QUERY_EDITING,
            //     PowerExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
            //     PowerExtensionModuleResolver::PERSISTED_QUERIES,
            //     PowerExtensionModuleResolver::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA,
            //     PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
            //     PremiumExtensionModuleResolver::POLYLANG,
            //     PowerExtensionModuleResolver::RESPONSE_ERROR_TRIGGER,
            // ],
            self::POWER_EXTENSIONS => [
                PowerExtensionModuleResolver::ACCESS_CONTROL,
                PowerExtensionModuleResolver::ACCESS_CONTROL_VISITOR_IP,
                PowerExtensionModuleResolver::CACHE_CONTROL,
                PowerExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
                PowerExtensionModuleResolver::CUSTOM_ENDPOINTS,
                PowerExtensionModuleResolver::DEPRECATION_NOTIFIER,
                PowerExtensionModuleResolver::EMAIL_SENDER,
                PowerExtensionModuleResolver::FIELD_DEFAULT_VALUE,
                PowerExtensionModuleResolver::FIELD_DEPRECATION,
                PowerExtensionModuleResolver::FIELD_ON_FIELD,
                PowerExtensionModuleResolver::FIELD_RESOLUTION_CACHING,
                PowerExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                PowerExtensionModuleResolver::FIELD_TO_INPUT,
                PowerExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                PowerExtensionModuleResolver::HELPER_FUNCTION_COLLECTION,
                PowerExtensionModuleResolver::HTTP_CLIENT,
                PowerExtensionModuleResolver::HTTP_REQUEST_VIA_SCHEMA,
                PowerExtensionModuleResolver::INTERNAL_GRAPHQL_SERVER,
                PowerExtensionModuleResolver::LOW_LEVEL_PERSISTED_QUERY_EDITING,
                PowerExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                PowerExtensionModuleResolver::PERSISTED_QUERIES,
                PowerExtensionModuleResolver::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA,
                PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                PowerExtensionModuleResolver::RESPONSE_ERROR_TRIGGER,
            ],
            self::ACCESS_CONTROL => [
                PowerExtensionModuleResolver::ACCESS_CONTROL,
                PowerExtensionModuleResolver::ACCESS_CONTROL_VISITOR_IP,
            ],
            self::CACHING => [
                PowerExtensionModuleResolver::CACHE_CONTROL,
                PowerExtensionModuleResolver::FIELD_RESOLUTION_CACHING,
            ],
            self::CUSTOM_ENDPOINTS => [
                PowerExtensionModuleResolver::CUSTOM_ENDPOINTS,
            ],
            self::DEPRECATION => [
                PowerExtensionModuleResolver::FIELD_DEPRECATION,
                PowerExtensionModuleResolver::DEPRECATION_NOTIFIER,
            ],
            self::HTTP_CLIENT => [
                PowerExtensionModuleResolver::HTTP_CLIENT,
            ],
            self::INTERNAL_GRAPHQL_SERVER => [
                PowerExtensionModuleResolver::INTERNAL_GRAPHQL_SERVER,
            ],
            self::MULTIPLE_QUERY_EXECUTION => [
                PowerExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
            ],
            self::PERSISTED_QUERIES => [
                PowerExtensionModuleResolver::PERSISTED_QUERIES,
                PowerExtensionModuleResolver::LOW_LEVEL_PERSISTED_QUERY_EDITING,
            ],
            self::QUERY_FUNCTIONS => [
                PowerExtensionModuleResolver::FIELD_TO_INPUT,
                PowerExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                PowerExtensionModuleResolver::FIELD_ON_FIELD,
                PowerExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
                PowerExtensionModuleResolver::FIELD_DEFAULT_VALUE,
                PowerExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                PowerExtensionModuleResolver::RESPONSE_ERROR_TRIGGER,
            ],
            self::SCHEMA_FUNCTIONS => [
                PowerExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                PowerExtensionModuleResolver::HTTP_REQUEST_VIA_SCHEMA,
                PowerExtensionModuleResolver::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA,
                PowerExtensionModuleResolver::EMAIL_SENDER,
                PowerExtensionModuleResolver::HELPER_FUNCTION_COLLECTION,
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
            // // "Gato GraphQL PRO" bundles all other bundles
            // self::PRO => array_diff(
            //     $this->getModulesToResolve(),
            //     [$module]
            // ),
            // self::ALL_EXTENSIONS => [
            //     self::ACCESS_CONTROL,
            //     self::CACHING,
            //     self::CUSTOM_ENDPOINTS,
            //     self::DEPRECATION,
            //     self::HTTP_CLIENT,
            //     self::INTERNAL_GRAPHQL_SERVER,
            //     self::MULTIPLE_QUERY_EXECUTION,
            //     self::PERSISTED_QUERIES,
            //     PremiumBundleExtensionModuleResolver::POLYLANG,
            //     self::QUERY_FUNCTIONS,
            //     self::SCHEMA_FUNCTIONS,
            // ],
            self::POWER_EXTENSIONS => [
                self::ACCESS_CONTROL,
                self::CACHING,
                self::CUSTOM_ENDPOINTS,
                self::DEPRECATION,
                self::HTTP_CLIENT,
                self::INTERNAL_GRAPHQL_SERVER,
                self::MULTIPLE_QUERY_EXECUTION,
                self::PERSISTED_QUERIES,
                self::QUERY_FUNCTIONS,
                self::SCHEMA_FUNCTIONS,
            ],
            default => [],
        };
    }
}
