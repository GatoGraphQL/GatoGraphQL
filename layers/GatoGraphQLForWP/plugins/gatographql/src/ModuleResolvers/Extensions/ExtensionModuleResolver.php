<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions;

use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\PluginStaticModuleConfiguration;

class ExtensionModuleResolver extends AbstractExtensionModuleResolver
{
    public const ACCESS_CONTROL = Plugin::NAMESPACE . '\\extensions\\access-control';
    public const ACCESS_CONTROL_VISITOR_IP = Plugin::NAMESPACE . '\\extensions\\access-control-visitor-ip';
    public const AUTOMATION = Plugin::NAMESPACE . '\\extensions\\automation';
    public const CACHE_CONTROL = Plugin::NAMESPACE . '\\extensions\\cache-control';
    public const CONDITIONAL_FIELD_MANIPULATION = Plugin::NAMESPACE . '\\extensions\\conditional-field-manipulation';
    public const CUSTOM_ENDPOINTS = Plugin::NAMESPACE . '\\extensions\\custom-endpoints';
    public const DEPRECATION_NOTIFIER = Plugin::NAMESPACE . '\\extensions\\deprecation-notifier';
    public const EMAIL_SENDER = Plugin::NAMESPACE . '\\extensions\\email-sender';
    public const EVENTS_MANAGER = Plugin::NAMESPACE . '\\extensions\\events-manager';
    public const FIELD_DEFAULT_VALUE = Plugin::NAMESPACE . '\\extensions\\field-default-value';
    public const FIELD_DEPRECATION = Plugin::NAMESPACE . '\\extensions\\field-deprecation';
    public const FIELD_ON_FIELD = Plugin::NAMESPACE . '\\extensions\\field-on-field';
    public const FIELD_RESOLUTION_CACHING = Plugin::NAMESPACE . '\\extensions\\field-resolution-caching';
    public const FIELD_RESPONSE_REMOVAL = Plugin::NAMESPACE . '\\extensions\\field-response-removal';
    public const FIELD_TO_INPUT = Plugin::NAMESPACE . '\\extensions\\field-to-input';
    public const FIELD_VALUE_ITERATION_AND_MANIPULATION = Plugin::NAMESPACE . '\\extensions\\field-value-iteration-and-manipulation';
    public const GOOGLE_TRANSLATE = Plugin::NAMESPACE . '\\extensions\\google-translate';
    public const HELPER_FUNCTION_COLLECTION = Plugin::NAMESPACE . '\\extensions\\helper-function-collection';
    public const HTTP_CLIENT = Plugin::NAMESPACE . '\\extensions\\http-client';
    public const HTTP_REQUEST_VIA_SCHEMA = Plugin::NAMESPACE . '\\extensions\\http-request-via-schema';
    public const INTERNAL_GRAPHQL_SERVER = Plugin::NAMESPACE . '\\extensions\\internal-graphql-server';
    public const LOW_LEVEL_PERSISTED_QUERY_EDITING = Plugin::NAMESPACE . '\\extensions\\low-level-persisted-query-editing';
    public const MULTILINGUALPRESS = Plugin::NAMESPACE . '\\extensions\\multilingualpress';
    public const MULTIPLE_QUERY_EXECUTION = Plugin::NAMESPACE . '\\extensions\\multiple-query-execution';
    public const PERSISTED_QUERIES = Plugin::NAMESPACE . '\\extensions\\persisted-queries';
    public const PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA = Plugin::NAMESPACE . '\\extensions\\php-constants-and-environment-variables-via-schema';
    public const PHP_FUNCTIONS_VIA_SCHEMA = Plugin::NAMESPACE . '\\extensions\\php-functions-via-schema';
    public const POLYLANG = Plugin::NAMESPACE . '\\extensions\\polylang';
    public const RESPONSE_ERROR_TRIGGER = Plugin::NAMESPACE . '\\extensions\\response-error-trigger';
    public const SCHEMA_EDITING_ACCESS = Plugin::NAMESPACE . '\\extensions\\schema-editing-access';

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return PluginStaticModuleConfiguration::offerGatoGraphQLPROExtensions() ? [
            self::ACCESS_CONTROL,
            self::ACCESS_CONTROL_VISITOR_IP,
            self::AUTOMATION,
            self::CACHE_CONTROL,
            self::CONDITIONAL_FIELD_MANIPULATION,
            self::CUSTOM_ENDPOINTS,
            self::DEPRECATION_NOTIFIER,
            self::EMAIL_SENDER,
            self::EVENTS_MANAGER,
            self::FIELD_DEFAULT_VALUE,
            self::FIELD_DEPRECATION,
            self::FIELD_ON_FIELD,
            self::FIELD_RESOLUTION_CACHING,
            self::FIELD_RESPONSE_REMOVAL,
            self::FIELD_TO_INPUT,
            self::FIELD_VALUE_ITERATION_AND_MANIPULATION,
            self::GOOGLE_TRANSLATE,
            self::HELPER_FUNCTION_COLLECTION,
            self::HTTP_CLIENT,
            self::HTTP_REQUEST_VIA_SCHEMA,
            self::INTERNAL_GRAPHQL_SERVER,
            self::LOW_LEVEL_PERSISTED_QUERY_EDITING,
            self::MULTILINGUALPRESS,
            self::MULTIPLE_QUERY_EXECUTION,
            self::PERSISTED_QUERIES,
            self::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA,
            self::PHP_FUNCTIONS_VIA_SCHEMA,
            self::POLYLANG,
            self::RESPONSE_ERROR_TRIGGER,
            self::SCHEMA_EDITING_ACCESS,
        ] : [];
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::ACCESS_CONTROL => \__('Access Control', 'gatographql'),
            self::ACCESS_CONTROL_VISITOR_IP => \__('Access Control: Visitor IP', 'gatographql'),
            self::AUTOMATION => \__('Automation', 'gatographql'),
            self::CACHE_CONTROL => \__('Cache Control', 'gatographql'),
            self::CONDITIONAL_FIELD_MANIPULATION => \__('Conditional Field Manipulation', 'gatographql'),
            self::CUSTOM_ENDPOINTS => \__('Custom Endpoints', 'gatographql'),
            self::DEPRECATION_NOTIFIER => \__('Deprecation Notifier', 'gatographql'),
            self::EMAIL_SENDER => \__('Email Sender', 'gatographql'),
            self::EVENTS_MANAGER => \__('Events Manager', 'gatographql'),
            self::FIELD_DEFAULT_VALUE => \__('Field Default Value', 'gatographql'),
            self::FIELD_DEPRECATION => \__('Field Deprecation', 'gatographql'),
            self::FIELD_ON_FIELD => \__('Field on Field', 'gatographql'),
            self::FIELD_RESOLUTION_CACHING => \__('Field Resolution Caching', 'gatographql'),
            self::FIELD_RESPONSE_REMOVAL => \__('Field Response Removal', 'gatographql'),
            self::FIELD_TO_INPUT => \__('Field To Input', 'gatographql'),
            self::FIELD_VALUE_ITERATION_AND_MANIPULATION => \__('Field Value Iteration and Manipulation', 'gatographql'),
            self::GOOGLE_TRANSLATE => \__('Google Translate', 'gatographql'),
            self::HELPER_FUNCTION_COLLECTION => \__('Helper Function Collection', 'gatographql'),
            self::HTTP_CLIENT => \__('HTTP Client', 'gatographql'),
            self::HTTP_REQUEST_VIA_SCHEMA => \__('HTTP Request via Schema', 'gatographql'),
            self::INTERNAL_GRAPHQL_SERVER => \__('Internal GraphQL Server', 'gatographql'),
            self::LOW_LEVEL_PERSISTED_QUERY_EDITING => \__('Low-Level Persisted Query Editing', 'gatographql'),
            self::MULTILINGUALPRESS => \__('MultilingualPress', 'gatographql'),
            self::MULTIPLE_QUERY_EXECUTION => \__('Multiple Query Execution', 'gatographql'),
            self::PERSISTED_QUERIES => \__('Persisted Queries', 'gatographql'),
            self::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA => \__('PHP Constants and Environment Variables via Schema', 'gatographql'),
            self::PHP_FUNCTIONS_VIA_SCHEMA => \__('PHP Functions via Schema', 'gatographql'),
            self::POLYLANG => \__('Polylang', 'gatographql'),
            self::RESPONSE_ERROR_TRIGGER => \__('Response Error Trigger', 'gatographql'),
            self::SCHEMA_EDITING_ACCESS => \__('Schema Editing Access', 'gatographql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::ACCESS_CONTROL => \__('Grant granular access to the schema, based on the user being logged-in (or not), having a certain role or capability, and more.', 'gatographql'),
            self::ACCESS_CONTROL_VISITOR_IP => \__('Grant access to the schema based on the visitor\'s IP address (Access Control extension is required).', 'gatographql'),
            self::AUTOMATION => \__('Use GraphQL to automate tasks in your app: Execute queries when some event happens, chain queries, and schedule and trigger queries via WP-Cron. (The Internal GraphQL Server extension is required).', 'gatographql'),
            self::CACHE_CONTROL => \__('Provide HTTP Caching for endpoints accessed via GET, with the max-age value automatically calculated from the query.', 'gatographql'),
            self::CONDITIONAL_FIELD_MANIPULATION => \__('Apply a directive on a field only if some condition is met.', 'gatographql'),
            self::CUSTOM_ENDPOINTS => \__('Provide multiple GraphQL endpoints to target different users and applications.', 'gatographql'),
            self::DEPRECATION_NOTIFIER => \__('Send deprecations in the response to the query (and not only when doing introspection).', 'gatographql'),
            self::EMAIL_SENDER => \__('Send emails via global mutation <code>_sendEmail</code>.', 'gatographql'),
            self::EVENTS_MANAGER => \__('Integration with plugin "Events Manager", adding fields to the schema to fetch event data.', 'gatographql'),
            self::FIELD_DEFAULT_VALUE => \__('Set a field to some default value (whenever it is <code>null</code> or empty).', 'gatographql'),
            self::FIELD_DEPRECATION => \__('Deprecate fields, and explain how to replace them, through a user interface.', 'gatographql'),
            self::FIELD_ON_FIELD => \__('Manipulate the value of a field by applying some other field on it.', 'gatographql'),
            self::FIELD_RESOLUTION_CACHING => \__('Cache and retrieve the response for expensive field operations.', 'gatographql'),
            self::FIELD_RESPONSE_REMOVAL => \__('Remove the output of a field from the response.', 'gatographql'),
            self::FIELD_TO_INPUT => \__('Retrieve the value of a field, manipulate it, and input it into another field, all within the same query.', 'gatographql'),
            self::FIELD_VALUE_ITERATION_AND_MANIPULATION => \__('Iterate and manipulate the value elements of array and object fields.', 'gatographql'),
            self::GOOGLE_TRANSLATE => \__('Translate content to multiple languages using the Google Translate API.', 'gatographql'),
            self::HELPER_FUNCTION_COLLECTION => \__('Collection of fields providing useful functionality.', 'gatographql'),
            self::HTTP_CLIENT => \__('Addition of fields to execute HTTP requests against a webserver and fetch their response.', 'gatographql'),
            self::HTTP_REQUEST_VIA_SCHEMA => \__('Addition of fields to retrieve the current HTTP request data.', 'gatographql'),
            self::INTERNAL_GRAPHQL_SERVER => \__('Execute GraphQL queries directly within your application, using PHP code.', 'gatographql'),
            self::LOW_LEVEL_PERSISTED_QUERY_EDITING => \__('Make normally-hidden directives (which inject some functionality into the GraphQL server) visible when editing a persisted query.', 'gatographql'),
            self::MULTILINGUALPRESS => \__('Integration with plugin "MultilingualPress", adding fields to the schema to fetch multilingual data.', 'gatographql'),
            self::MULTIPLE_QUERY_EXECUTION => \__('Combine multiple queries into a single query, sharing state across them and executing them in the requested order.', 'gatographql'),
            self::PERSISTED_QUERIES => \__('Expose GraphQL endpoints with a predefined response, similar to REST endpoints.', 'gatographql'),
            self::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA => \__('Query the value from an environment variable or PHP constant.', 'gatographql'),
            self::PHP_FUNCTIONS_VIA_SCHEMA => \__('Manipulate the field output using standard programming language functions available in PHP.', 'gatographql'),
            self::POLYLANG => \__('Integration with plugin "Polylang", adding fields to the schema to fetch multilingual data.', 'gatographql'),
            self::RESPONSE_ERROR_TRIGGER => \__('Explicitly add an error entry to the response to trigger the failure of the GraphQL request (whenever a field does not meet the expected conditions).', 'gatographql'),
            self::SCHEMA_EDITING_ACCESS => \__('Grant access to users other than admins to edit the GraphQL schema.', 'gatographql'),
            default => parent::getDescription($module),
        };
    }
}
