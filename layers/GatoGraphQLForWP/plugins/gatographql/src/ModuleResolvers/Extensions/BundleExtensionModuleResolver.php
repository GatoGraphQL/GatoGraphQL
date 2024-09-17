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
    public const EASY_WORDPRESS_BULK_TRANSFORM_AND_UPDATE = Plugin::NAMESPACE . '\\bundle-extensions\\custom-endpoints';
    public const PRIVATE_GRAPHQL_SERVER_FOR_WORDPRESS = Plugin::NAMESPACE . '\\bundle-extensions\\private-graphql-server-for-wordpress';
    public const RESPONSIBLE_WORDPRESS_PUBLIC_API = Plugin::NAMESPACE . '\\bundle-extensions\\responsible-wordpress-public-api';
    public const SELECTIVE_CONTENT_IMPORT_EXPORT_AND_SYNC_FOR_WORDPRESS = Plugin::NAMESPACE . '\\bundle-extensions\\selective-content-import-export-and-sync-for-wordpress';
    public const SIMPLEST_WORDPRESS_CONTENT_TRANSLATION = Plugin::NAMESPACE . '\\bundle-extensions\\simplest-wordpress-content-translation';
    public const TAILORED_WORDPRESS_AUTOMATOR = Plugin::NAMESPACE . '\\bundle-extensions\\tailored-wordpress-automator';
    public const UNHINDERED_WORDPRESS_EMAIL_NOTIFICATIONS = Plugin::NAMESPACE . '\\bundle-extensions\\unhindered-wordpress-email-notifications';
    public const VERSATILE_WORDPRESS_REQUEST_API = Plugin::NAMESPACE . '\\bundle-extensions\\versatile-wordpress-request-api';

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
                self::EASY_WORDPRESS_BULK_TRANSFORM_AND_UPDATE,
                self::PRIVATE_GRAPHQL_SERVER_FOR_WORDPRESS,
                self::RESPONSIBLE_WORDPRESS_PUBLIC_API,
                self::SELECTIVE_CONTENT_IMPORT_EXPORT_AND_SYNC_FOR_WORDPRESS,
                self::SIMPLEST_WORDPRESS_CONTENT_TRANSLATION,
                self::TAILORED_WORDPRESS_AUTOMATOR,
                self::UNHINDERED_WORDPRESS_EMAIL_NOTIFICATIONS,
                self::VERSATILE_WORDPRESS_REQUEST_API,
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
            self::EASY_WORDPRESS_BULK_TRANSFORM_AND_UPDATE => sprintf($placeholder, \__('Easy WordPress Bulk Transform & Update', 'gatographql')),
            self::PRIVATE_GRAPHQL_SERVER_FOR_WORDPRESS => sprintf($placeholder, \__('Private GraphQL Server for WordPress', 'gatographql')),
            self::RESPONSIBLE_WORDPRESS_PUBLIC_API => sprintf($placeholder, \__('Responsible WordPress Public API', 'gatographql')),
            self::SELECTIVE_CONTENT_IMPORT_EXPORT_AND_SYNC_FOR_WORDPRESS => sprintf($placeholder, \__('Selective Content Import, Export & Sync for WordPress', 'gatographql')),
            self::SIMPLEST_WORDPRESS_CONTENT_TRANSLATION => sprintf($placeholder, \__('Simplest WordPress Content Translation', 'gatographql')),
            self::TAILORED_WORDPRESS_AUTOMATOR => sprintf($placeholder, \__('Tailored WordPress Automator', 'gatographql')),
            self::UNHINDERED_WORDPRESS_EMAIL_NOTIFICATIONS => sprintf($placeholder, \__('Unhindered WordPress Email Notifications', 'gatographql')),
            self::VERSATILE_WORDPRESS_REQUEST_API => sprintf($placeholder, \__('Versatile WordPress Request API', 'gatographql')),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::PRO => \__('All the PRO extensions for Gato GraphQL, the most powerful GraphQL server for WordPress', 'gatographql'),
            self::ALL_FEATURE_BUNDLED_EXTENSIONS => \__('All of Gato GraphQL extensions, in a single plugin', 'gatographql'),
            self::CACHING => \__('Make your application faster by providing HTTP Caching for the GraphQL response, and by caching the results of expensive operations', 'gatographql'),
            self::EASY_WORDPRESS_BULK_TRANSFORM_AND_UPDATE => \__('Transform hundreds of posts with a single operation (replacing strings, adding blocks, adding a thumbnail, and more), and store them again on the DB', 'gatographql'),
            self::PRIVATE_GRAPHQL_SERVER_FOR_WORDPRESS => \__('Use GraphQL to power your application (blocks, themes and plugins), internally fetching data without exposing a public endpoint', 'gatographql'),
            self::RESPONSIBLE_WORDPRESS_PUBLIC_API => \__('Enhance your public APIs with additional layers of security, speed, power, schema evolution and control', 'gatographql'),
            self::SELECTIVE_CONTENT_IMPORT_EXPORT_AND_SYNC_FOR_WORDPRESS => \__('Import hundreds of records into your WordPress site from another site or service (such as Google Sheets), and selectively export entries to another site', 'gatographql'),
            self::SIMPLEST_WORDPRESS_CONTENT_TRANSLATION => \__('Translate your content into over 130 languages using the Google Translate API, without adding extra tables or inner joins to the DB', 'gatographql'),
            self::TAILORED_WORDPRESS_AUTOMATOR => \__('Create workflows to automate tasks (to transform data, automatically caption images, send notifications, and more)', 'gatographql'),
            self::UNHINDERED_WORDPRESS_EMAIL_NOTIFICATIONS => \__('Send personalized emails to all your users, and notifications to the admin, without constraints on what data can be added to the email', 'gatographql'),
            self::VERSATILE_WORDPRESS_REQUEST_API => \__('Interact with any external API and cloud service, posting and fetching data to/from them', 'gatographql'),
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
                // @todo Complete bundled extensions here
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
            self::EASY_WORDPRESS_BULK_TRANSFORM_AND_UPDATE => [
                ExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
                ExtensionModuleResolver::FIELD_DEFAULT_VALUE,
                ExtensionModuleResolver::FIELD_ON_FIELD,
                ExtensionModuleResolver::FIELD_TO_INPUT,
                ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                ExtensionModuleResolver::HELPER_FUNCTION_COLLECTION,
                ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
            ],
            self::PRIVATE_GRAPHQL_SERVER_FOR_WORDPRESS => [
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
            self::RESPONSIBLE_WORDPRESS_PUBLIC_API => [
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
            self::SELECTIVE_CONTENT_IMPORT_EXPORT_AND_SYNC_FOR_WORDPRESS => [
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
            self::SIMPLEST_WORDPRESS_CONTENT_TRANSLATION => [
                ExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
                ExtensionModuleResolver::FIELD_ON_FIELD,
                ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                ExtensionModuleResolver::FIELD_TO_INPUT,
                ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                ExtensionModuleResolver::GOOGLE_TRANSLATE,
                ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
            ],
            self::TAILORED_WORDPRESS_AUTOMATOR => [
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
            self::UNHINDERED_WORDPRESS_EMAIL_NOTIFICATIONS => [
                ExtensionModuleResolver::EMAIL_SENDER,
                ExtensionModuleResolver::FIELD_ON_FIELD,
                ExtensionModuleResolver::FIELD_TO_INPUT,
                ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                ExtensionModuleResolver::HELPER_FUNCTION_COLLECTION,
                ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                ExtensionModuleResolver::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA,
                ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
            ],
            self::VERSATILE_WORDPRESS_REQUEST_API => [
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
                // @todo Complete bundled bundles here
            ],
            self::PRIVATE_GRAPHQL_SERVER_FOR_WORDPRESS => [
                self::SELECTIVE_CONTENT_IMPORT_EXPORT_AND_SYNC_FOR_WORDPRESS,
                self::EASY_WORDPRESS_BULK_TRANSFORM_AND_UPDATE,
                self::UNHINDERED_WORDPRESS_EMAIL_NOTIFICATIONS,
                self::VERSATILE_WORDPRESS_REQUEST_API,
            ],
            self::CACHING => [
                self::EASY_WORDPRESS_BULK_TRANSFORM_AND_UPDATE,
                self::UNHINDERED_WORDPRESS_EMAIL_NOTIFICATIONS,
                self::VERSATILE_WORDPRESS_REQUEST_API,
            ],
            self::TAILORED_WORDPRESS_AUTOMATOR => [
                self::PRIVATE_GRAPHQL_SERVER_FOR_WORDPRESS,
                self::SELECTIVE_CONTENT_IMPORT_EXPORT_AND_SYNC_FOR_WORDPRESS,
                self::EASY_WORDPRESS_BULK_TRANSFORM_AND_UPDATE,
                self::UNHINDERED_WORDPRESS_EMAIL_NOTIFICATIONS,
                self::VERSATILE_WORDPRESS_REQUEST_API,
            ],
            self::SELECTIVE_CONTENT_IMPORT_EXPORT_AND_SYNC_FOR_WORDPRESS => [
                self::EASY_WORDPRESS_BULK_TRANSFORM_AND_UPDATE,
            ],
            self::VERSATILE_WORDPRESS_REQUEST_API => [
                self::SELECTIVE_CONTENT_IMPORT_EXPORT_AND_SYNC_FOR_WORDPRESS,
                self::EASY_WORDPRESS_BULK_TRANSFORM_AND_UPDATE,
            ],
            default => [],
        };
    }
}
