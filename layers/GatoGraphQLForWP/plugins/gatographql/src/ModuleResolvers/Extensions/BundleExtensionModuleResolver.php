<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions;

use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\PluginApp;

class BundleExtensionModuleResolver extends AbstractBundleExtensionModuleResolver
{
    public const ALL_EXTENSIONS = Plugin::NAMESPACE . '\\bundle-extensions\\all-extensions';
    public const AUTOMATED_CONTENT_TRANSLATION_AND_SYNC_FOR_WORDPRESS_MULTISITE = Plugin::NAMESPACE . '\\bundle-extensions\\automated-content-translation-and-sync-for-wordpress-multisite';
    public const PRIVATE_GRAPHQL_SERVER_FOR_WORDPRESS = Plugin::NAMESPACE . '\\bundle-extensions\\private-graphql-server-for-wordpress';
    public const WORDPRESS_AS_API_GATEWAY = Plugin::NAMESPACE . '\\bundle-extensions\\wordpress-as-api-gateway';
    public const WORDPRESS_AUTOMATOR = Plugin::NAMESPACE . '\\bundle-extensions\\wordpress-automator';
    public const WORDPRESS_CONTENT_IMPORT_EXPORT_AND_SYNC = Plugin::NAMESPACE . '\\bundle-extensions\\wordpress-content-import-export-and-sync';
    public const WORDPRESS_CONTENT_TRANSFORMER = Plugin::NAMESPACE . '\\bundle-extensions\\wordpress-content-transformer';
    public const WORDPRESS_CONTENT_TRANSLATION = Plugin::NAMESPACE . '\\bundle-extensions\\wordpress-content-translation';
    public const WORDPRESS_EMAIL_NOTIFICATIONS = Plugin::NAMESPACE . '\\bundle-extensions\\wordpress-email-notifications';
    public const WORDPRESS_PUBLIC_API = Plugin::NAMESPACE . '\\bundle-extensions\\wordpress-public-api';
    public const WORDPRESS_REMOTE_REQUEST_AND_PROCESS = Plugin::NAMESPACE . '\\bundle-extensions\\wordpress-remote-request-and-process';

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::ALL_EXTENSIONS,
            self::AUTOMATED_CONTENT_TRANSLATION_AND_SYNC_FOR_WORDPRESS_MULTISITE,
            self::PRIVATE_GRAPHQL_SERVER_FOR_WORDPRESS,
            self::WORDPRESS_AS_API_GATEWAY,
            self::WORDPRESS_AUTOMATOR,
            self::WORDPRESS_CONTENT_IMPORT_EXPORT_AND_SYNC,
            self::WORDPRESS_CONTENT_TRANSFORMER,
            self::WORDPRESS_CONTENT_TRANSLATION,
            self::WORDPRESS_EMAIL_NOTIFICATIONS,
            self::WORDPRESS_PUBLIC_API,
            self::WORDPRESS_REMOTE_REQUEST_AND_PROCESS,
        ];
    }

    public function getName(string $module): string
    {
        $placeholder = \__('“%s” Bundle', 'gatographql');
        return match ($module) {
            self::ALL_EXTENSIONS => sprintf($placeholder, \__('All Extensions', 'gatographql')),
            self::AUTOMATED_CONTENT_TRANSLATION_AND_SYNC_FOR_WORDPRESS_MULTISITE => sprintf($placeholder, \__('Automated Content Translation & Sync for WordPress Multisite', 'gatographql')),
            self::PRIVATE_GRAPHQL_SERVER_FOR_WORDPRESS => sprintf($placeholder, \__('Private GraphQL Server for WordPress', 'gatographql')),
            self::WORDPRESS_AS_API_GATEWAY => sprintf($placeholder, \__('WordPress as API Gateway', 'gatographql')),
            self::WORDPRESS_AUTOMATOR => sprintf($placeholder, \__('WordPress Automator', 'gatographql')),
            self::WORDPRESS_CONTENT_IMPORT_EXPORT_AND_SYNC => sprintf($placeholder, \__('WordPress Content Import, Export & Sync', 'gatographql')),
            self::WORDPRESS_CONTENT_TRANSFORMER => sprintf($placeholder, \__('WordPress Content Transformer', 'gatographql')),
            self::WORDPRESS_CONTENT_TRANSLATION => sprintf($placeholder, \__('WordPress Content Translation', 'gatographql')),
            self::WORDPRESS_EMAIL_NOTIFICATIONS => sprintf($placeholder, \__('WordPress Email Notifications', 'gatographql')),
            self::WORDPRESS_PUBLIC_API => sprintf($placeholder, \__('WordPress Public API', 'gatographql')),
            self::WORDPRESS_REMOTE_REQUEST_AND_PROCESS => sprintf($placeholder, \__('WordPress Remote Request & Process', 'gatographql')),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::ALL_EXTENSIONS => \__('All of Gato GraphQL extensions, in a single plugin. As new extensions are created and released, they will also be added to this bundle.', 'gatographql'),
            self::AUTOMATED_CONTENT_TRANSLATION_AND_SYNC_FOR_WORDPRESS_MULTISITE => \__('@todo New bundle', 'gatographql'),
            self::PRIVATE_GRAPHQL_SERVER_FOR_WORDPRESS => \__('@todo New bundle', 'gatographql'),
            self::WORDPRESS_AS_API_GATEWAY => \__('@todo New bundle', 'gatographql'),
            self::WORDPRESS_AUTOMATOR => \__('Keep content in sync, help migrate websites, send notifications, interact with 3rd-party services and APIs, create automation workflows, and more.', 'gatographql'),
            self::WORDPRESS_CONTENT_IMPORT_EXPORT_AND_SYNC => \__('@todo New bundle', 'gatographql'),
            self::WORDPRESS_CONTENT_TRANSFORMER => \__('@todo New bundle', 'gatographql'),
            self::WORDPRESS_CONTENT_TRANSLATION => \__('Translate content via the Google Translate API, even within the deep structure of (Gutenberg) blocks.', 'gatographql'),
            self::WORDPRESS_EMAIL_NOTIFICATIONS => \__('@todo New bundle', 'gatographql'),
            self::WORDPRESS_PUBLIC_API => \__('Expose your public APIs in a secure manner, make them faster through caching, leverage tools to access data, and evolve the schema.', 'gatographql'),
            self::WORDPRESS_REMOTE_REQUEST_AND_PROCESS => \__('@todo New bundle', 'gatographql'),
            default => parent::getDescription($module),
        };
    }

    public function getPriority(): int
    {
        return 20;
    }

    public function getLogoURL(string $module): string
    {
        return match ($module) {
            self::ALL_EXTENSIONS => PluginApp::getMainPlugin()->getPluginURL() . 'assets/img/logos/GatoGraphQL-logo-face.png',
            default => parent::getLogoURL($module),
        };
    }

    /**
     * @return string[]
     */
    public function getBundledExtensionModules(string $module): array
    {
        return match ($module) {
            self::ALL_EXTENSIONS => [
                ExtensionModuleResolver::ACCESS_CONTROL,
                ExtensionModuleResolver::ACCESS_CONTROL_VISITOR_IP,
                ExtensionModuleResolver::AUTOMATION,
                ExtensionModuleResolver::CACHE_CONTROL,
                ExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
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
                ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                ExtensionModuleResolver::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA,
                ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ExtensionModuleResolver::RESPONSE_ERROR_TRIGGER,
                ExtensionModuleResolver::SCHEMA_EDITING_ACCESS,
            ],
            self::AUTOMATED_CONTENT_TRANSLATION_AND_SYNC_FOR_WORDPRESS_MULTISITE => [
                ExtensionModuleResolver::AUTOMATION,
                ExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
                ExtensionModuleResolver::FIELD_ON_FIELD,
                ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                ExtensionModuleResolver::FIELD_TO_INPUT,
                ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                ExtensionModuleResolver::GOOGLE_TRANSLATE,
                ExtensionModuleResolver::HTTP_CLIENT,
                ExtensionModuleResolver::INTERNAL_GRAPHQL_SERVER,
                ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                ExtensionModuleResolver::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA,
                ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
                ExtensionModuleResolver::RESPONSE_ERROR_TRIGGER,
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
            self::WORDPRESS_AS_API_GATEWAY => [
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
            self::WORDPRESS_AUTOMATOR => [
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
            self::WORDPRESS_CONTENT_IMPORT_EXPORT_AND_SYNC => [
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
            self::WORDPRESS_CONTENT_TRANSFORMER => [
                ExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
                ExtensionModuleResolver::FIELD_DEFAULT_VALUE,
                ExtensionModuleResolver::FIELD_ON_FIELD,
                ExtensionModuleResolver::FIELD_TO_INPUT,
                ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                ExtensionModuleResolver::HELPER_FUNCTION_COLLECTION,
                ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
            ],
            self::WORDPRESS_CONTENT_TRANSLATION => [
                ExtensionModuleResolver::CONDITIONAL_FIELD_MANIPULATION,
                ExtensionModuleResolver::FIELD_ON_FIELD,
                ExtensionModuleResolver::FIELD_RESPONSE_REMOVAL,
                ExtensionModuleResolver::FIELD_TO_INPUT,
                ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                ExtensionModuleResolver::GOOGLE_TRANSLATE,
                ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
            ],
            self::WORDPRESS_EMAIL_NOTIFICATIONS => [
                ExtensionModuleResolver::EMAIL_SENDER,
                ExtensionModuleResolver::FIELD_ON_FIELD,
                ExtensionModuleResolver::FIELD_TO_INPUT,
                ExtensionModuleResolver::FIELD_VALUE_ITERATION_AND_MANIPULATION,
                ExtensionModuleResolver::HELPER_FUNCTION_COLLECTION,
                ExtensionModuleResolver::MULTIPLE_QUERY_EXECUTION,
                ExtensionModuleResolver::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA,
                ExtensionModuleResolver::PHP_FUNCTIONS_VIA_SCHEMA,
            ],
            self::WORDPRESS_PUBLIC_API => [
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
            self::WORDPRESS_REMOTE_REQUEST_AND_PROCESS => [
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
            // "All Extensions" bundles all other bundles
            self::ALL_EXTENSIONS => array_diff(
                $this->getModulesToResolve(),
                [$module]
            ),
            self::AUTOMATED_CONTENT_TRANSLATION_AND_SYNC_FOR_WORDPRESS_MULTISITE => [
                self::WORDPRESS_CONTENT_TRANSLATION,
            ],
            self::PRIVATE_GRAPHQL_SERVER_FOR_WORDPRESS => [
                self::WORDPRESS_CONTENT_IMPORT_EXPORT_AND_SYNC,
                self::WORDPRESS_CONTENT_TRANSFORMER,
                self::WORDPRESS_EMAIL_NOTIFICATIONS,
                self::WORDPRESS_REMOTE_REQUEST_AND_PROCESS,
            ],
            self::WORDPRESS_AS_API_GATEWAY => [
                self::WORDPRESS_CONTENT_TRANSFORMER,
                self::WORDPRESS_EMAIL_NOTIFICATIONS,
                self::WORDPRESS_REMOTE_REQUEST_AND_PROCESS,
            ],
            self::WORDPRESS_AUTOMATOR => [
                self::PRIVATE_GRAPHQL_SERVER_FOR_WORDPRESS,
                self::WORDPRESS_CONTENT_IMPORT_EXPORT_AND_SYNC,
                self::WORDPRESS_CONTENT_TRANSFORMER,
                self::WORDPRESS_EMAIL_NOTIFICATIONS,
                self::WORDPRESS_REMOTE_REQUEST_AND_PROCESS,
            ],
            self::WORDPRESS_CONTENT_IMPORT_EXPORT_AND_SYNC => [
                self::WORDPRESS_CONTENT_TRANSFORMER,
            ],
            self::WORDPRESS_REMOTE_REQUEST_AND_PROCESS => [
                self::WORDPRESS_CONTENT_IMPORT_EXPORT_AND_SYNC,
                self::WORDPRESS_CONTENT_TRANSFORMER,
            ],
            default => [],
        };
    }
}
