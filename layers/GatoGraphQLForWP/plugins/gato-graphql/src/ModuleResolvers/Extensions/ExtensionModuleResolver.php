<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\Plugin;

class ExtensionModuleResolver extends AbstractExtensionModuleResolver
{
    public const GATO_GRAPHQL_PRO = Plugin::NAMESPACE . '\\extensions\\gato-graphql-pro';
    public const ACCESS_CONTROL = Plugin::NAMESPACE . '\\extensions\\access-control';
    public const ACCESS_CONTROL_VISITOR_IP = Plugin::NAMESPACE . '\\extensions\\access-control-visitor-ip';
    public const CACHE_CONTROL = Plugin::NAMESPACE . '\\extensions\\cache-control';
    public const CONDITIONAL_FIELD_MANIPULATION = Plugin::NAMESPACE . '\\extensions\\conditional-field-manipulation';
    public const DEPRECATION_NOTIFIER = Plugin::NAMESPACE . '\\extensions\\deprecation-notifier';
    public const EMAIL_SENDER = Plugin::NAMESPACE . '\\extensions\\email-sender';
    public const EVENTS_MANAGER = Plugin::NAMESPACE . '\\extensions\\events-manager';
    public const FIELD_DEFAULT_VALUE = Plugin::NAMESPACE . '\\extensions\\field-default-value';
    public const FIELD_DEPRECATION = Plugin::NAMESPACE . '\\extensions\\field-deprecation';
    public const FIELD_ON_FIELD = Plugin::NAMESPACE . '\\extensions\\field-on-field';
    public const FIELD_RESOLUTION_CACHING = Plugin::NAMESPACE . '\\extensions\\field-resolution-caching';
    public const FIELD_RESPONSE_REMOVAL = Plugin::NAMESPACE . '\\extensions\\field-response-removal';
    public const FIELD_TO_INPUT = Plugin::NAMESPACE . '\\extensions\\field-to-input';
    public const FIELD_VALUE_DATA_EXTRACTION_MANIPULATION = Plugin::NAMESPACE . '\\extensions\\field-value-data-extraction-and-manipulation';
    public const FIELD_VALUE_ITERATION_AND_MANIPULATION = Plugin::NAMESPACE . '\\extensions\\field-value-iteration-and-manipulation';
    public const GOOGLE_TRANSLATE = Plugin::NAMESPACE . '\\extensions\\google-translate';
    public const HELPER_FUNCTION_COLLECTION = Plugin::NAMESPACE . '\\extensions\\helper-function-collection';
    public const HTTP_CLIENT = Plugin::NAMESPACE . '\\extensions\\http-client';
    public const HTTP_REQUEST_VIA_SCHEMA = Plugin::NAMESPACE . '\\extensions\\http-request-via-schema';
    public const INTERNAL_GRAPHQL_SERVER = Plugin::NAMESPACE . '\\extensions\\internal-graphql-server';
    public const LOW_LEVEL_PERSISTED_QUERY_EDITING = Plugin::NAMESPACE . '\\extensions\\low-level-persisted-query-editing';
    public const MULTIPLE_QUERY_EXECUTION = Plugin::NAMESPACE . '\\extensions\\multiple-query-execution';
    public const PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA = Plugin::NAMESPACE . '\\extensions\\php-constants-and-environment-variables-via-schema';
    public const PHP_FUNCTIONS_VIA_SCHEMA = Plugin::NAMESPACE . '\\extensions\\php-functions-via-schema';
    public const RESPONSE_ERROR_TRIGGER = Plugin::NAMESPACE . '\\extensions\\response-error-trigger';
    public const SCHEMA_EDITING_ACCESS = Plugin::NAMESPACE . '\\extensions\\schema-editing-access';

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::GATO_GRAPHQL_PRO,
            self::ACCESS_CONTROL,
            self::ACCESS_CONTROL_VISITOR_IP,
            self::CACHE_CONTROL,
            self::CONDITIONAL_FIELD_MANIPULATION,
            self::DEPRECATION_NOTIFIER,
            self::EMAIL_SENDER,
            self::EVENTS_MANAGER,
            self::FIELD_DEFAULT_VALUE,
            self::FIELD_DEPRECATION,
            self::FIELD_ON_FIELD,
            self::FIELD_RESOLUTION_CACHING,
            self::FIELD_RESPONSE_REMOVAL,
            self::FIELD_TO_INPUT,
            self::FIELD_VALUE_DATA_EXTRACTION_MANIPULATION,
            self::FIELD_VALUE_ITERATION_AND_MANIPULATION,
            self::GOOGLE_TRANSLATE,
            self::HELPER_FUNCTION_COLLECTION,
            self::HTTP_CLIENT,
            self::HTTP_REQUEST_VIA_SCHEMA,
            self::INTERNAL_GRAPHQL_SERVER,
            self::LOW_LEVEL_PERSISTED_QUERY_EDITING,
            self::MULTIPLE_QUERY_EXECUTION,
            self::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA,
            self::PHP_FUNCTIONS_VIA_SCHEMA,
            self::RESPONSE_ERROR_TRIGGER,
            self::SCHEMA_EDITING_ACCESS,
        ];
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::GATO_GRAPHQL_PRO => \__('Gato GraphQL PRO', 'gato-graphql'),
            self::ACCESS_CONTROL => \__('Access Control', 'gato-graphql'),
            self::ACCESS_CONTROL_VISITOR_IP => \__('Access Control: Visitor IP', 'gato-graphql'),
            self::CACHE_CONTROL => \__('Cache Control', 'gato-graphql'),
            self::CONDITIONAL_FIELD_MANIPULATION => \__('Conditional Field Manipulation', 'gato-graphql'),
            self::DEPRECATION_NOTIFIER => \__('Deprecation Notifier', 'gato-graphql'),
            self::EMAIL_SENDER => \__('Email Sender', 'gato-graphql'),
            self::EVENTS_MANAGER => \__('Events Manager', 'gato-graphql'),
            self::FIELD_DEFAULT_VALUE => \__('Field Default Value', 'gato-graphql'),
            self::FIELD_DEPRECATION => \__('Field Deprecation', 'gato-graphql'),
            self::FIELD_ON_FIELD => \__('Field on Field', 'gato-graphql'),
            self::FIELD_RESOLUTION_CACHING => \__('Field Resolution Caching', 'gato-graphql'),
            self::FIELD_RESPONSE_REMOVAL => \__('Field Response Removal', 'gato-graphql'),
            self::FIELD_TO_INPUT => \__('Field To Input', 'gato-graphql'),
            self::FIELD_VALUE_DATA_EXTRACTION_MANIPULATION => \__('Field Value Data Extraction and Manipulation', 'gato-graphql'),
            self::FIELD_VALUE_ITERATION_AND_MANIPULATION => \__('Field Value Interation and Manipulation', 'gato-graphql'),
            self::GOOGLE_TRANSLATE => \__('Google Translate', 'gato-graphql'),
            self::HELPER_FUNCTION_COLLECTION => \__('Helper Function Collection', 'gato-graphql'),
            self::HTTP_CLIENT => \__('HTTP Client', 'gato-graphql'),
            self::HTTP_REQUEST_VIA_SCHEMA => \__('HTTP Request via Schema', 'gato-graphql'),
            self::INTERNAL_GRAPHQL_SERVER => \__('Internal GraphQL Server', 'gato-graphql'),
            self::LOW_LEVEL_PERSISTED_QUERY_EDITING => \__('Low-Level Persisted Query Editing', 'gato-graphql'),
            self::MULTIPLE_QUERY_EXECUTION => \__('Multiple Query Execution', 'gato-graphql'),
            self::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA => \__('PHP Constants and Environment Variables via Schema', 'gato-graphql'),
            self::PHP_FUNCTIONS_VIA_SCHEMA => \__('PHP Functions via Schema', 'gato-graphql'),
            self::RESPONSE_ERROR_TRIGGER => \__('Response Error Trigger', 'gato-graphql'),
            self::SCHEMA_EDITING_ACCESS => \__('Schema Editing Access', 'gato-graphql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::GATO_GRAPHQL_PRO => \__('Superpower your app with PRO features.', 'gato-graphql'),
            self::ACCESS_CONTROL => \__('Grant user access to schema elements via Access Control Lists.', 'gato-graphql'),
            self::ACCESS_CONTROL_VISITOR_IP => \__('Grant access to schema elements based on the visitor\'s IP address (Access Control extension is rquired).', 'gato-graphql'),
            self::CACHE_CONTROL => \__('Provide HTTP Caching for endpoints accessed via GET, with the max-age value automatically calculated from the query.', 'gato-graphql'),
            self::CONDITIONAL_FIELD_MANIPULATION => \__('Apply a directive on a field only if some condition is met.', 'gato-graphql'),
            self::DEPRECATION_NOTIFIER => \__('Send deprecations in the response to the query (and not only when doing introspection).', 'gato-graphql'),
            self::EMAIL_SENDER => \__('Send emails via global mutation <code>_sendEmail</code>.', 'gato-graphql'),
            self::EVENTS_MANAGER => \__('Integration with plugin "Events Manager", adding fields to the schema to fetch event data.', 'gato-graphql'),
            self::FIELD_DEFAULT_VALUE => \__('Set a field to some default value, whenever it is <code>null</code> or empty.', 'gato-graphql'),
            self::FIELD_DEPRECATION => \__('Deprecate fields, and explain how to replace them, through a user interface.', 'gato-graphql'),
            self::FIELD_ON_FIELD => \__('Manipulate the value of a field by applying some other field on it.', 'gato-graphql'),
            self::FIELD_RESOLUTION_CACHING => \__('Cache and retrieve the response for expensive field operations.', 'gato-graphql'),
            self::FIELD_RESPONSE_REMOVAL => \__('Remove the output of a field from the response.', 'gato-graphql'),
            self::FIELD_TO_INPUT => \__('Retrieve the value of a field, manipulate it, and input it into another field, all within the same query.', 'gato-graphql'),
            self::FIELD_VALUE_DATA_EXTRACTION_MANIPULATION => \__('Extract and manipulate the deep inner values of array and object fields.', 'gato-graphql'),
            self::FIELD_VALUE_ITERATION_AND_MANIPULATION => \__('Iterate and manipulate the value elements of array and object fields.', 'gato-graphql'),
            self::GOOGLE_TRANSLATE => \__('Translate content to multiple languages using the Google Translate API.', 'gato-graphql'),
            self::HELPER_FUNCTION_COLLECTION => \__('Collection of fields providing useful functionality.', 'gato-graphql'),
            self::HTTP_CLIENT => \__('Addition of fields to execute HTTP requests against a webserver and fetch their response.', 'gato-graphql'),
            self::HTTP_REQUEST_VIA_SCHEMA => \__('Addition of fields to retrieve the current HTTP request data.', 'gato-graphql'),
            self::INTERNAL_GRAPHQL_SERVER => \__('Execute GraphQL queries directly within your application (without accessing an endpoint), using PHP code.', 'gato-graphql'),
            self::LOW_LEVEL_PERSISTED_QUERY_EDITING => \__('Make normally-hidden directives (which inject some functionality into the GraphQL server) visible when editing a persisted query.', 'gato-graphql'),
            self::MULTIPLE_QUERY_EXECUTION => \__('Combine multiple queries into a single query, sharing state across them and making sure they are executed in the requested order.', 'gato-graphql'),
            self::PHP_CONSTANTS_AND_ENVIRONMENT_VARIABLES_VIA_SCHEMA => \__('Query the value from an environment variable or PHP constant.', 'gato-graphql'),
            self::PHP_FUNCTIONS_VIA_SCHEMA => \__('Manipulate the field output using standard programming language functions available in PHP.', 'gato-graphql'),
            self::RESPONSE_ERROR_TRIGGER => \__('Explicitly add an error entry to the response to trigger the failure of the GraphQL request (whenever a field does not meet the expected conditions).', 'gato-graphql'),
            self::SCHEMA_EDITING_ACCESS => \__('Grant access to users other than admins to edit the GraphQL schema.', 'gato-graphql'),
            default => parent::getDescription($module),
        };
    }

    public function getGatoGraphQLExtensionSlug(string $module): string
    {
        return match ($module) {
            self::GATO_GRAPHQL_PRO => $this->getSlug($module),
            default => parent::getGatoGraphQLExtensionSlug($module),
        };
    }

    public function getWebsiteURL(string $module): string
    {
        if ($module === self::GATO_GRAPHQL_PRO) {
            /** @var ModuleConfiguration */
            $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
            return sprintf(
                '%s/pro',
                $moduleConfiguration->getGatoGraphQLWebsiteURL()
            );
        }

        return parent::getWebsiteURL($module);
    }

    public function getLogoURL(string $module): string
    {
        $logoURL = parent::getLogoURL($module);
        if ($module === self::GATO_GRAPHQL_PRO) {
            return $logoURL;
        }

        return str_replace(
            'GatoGraphQL-logo.png',
            'GatoGraphQL-logo2.png',
            $logoURL,
        );
    }
}
