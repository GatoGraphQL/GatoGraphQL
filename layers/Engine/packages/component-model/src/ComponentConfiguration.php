<?php

declare(strict_types=1);

namespace PoP\ComponentModel;

use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;
use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\Constants\Params;
use PoP\ComponentModel\Tokens\Param;
use PoP\Root\Environment as RootEnvironment;

class ComponentConfiguration
{
    use ComponentConfigurationTrait;

    /**
     * Map with the configuration passed by params
     *
     * @var array
     */
    private static array $overrideConfiguration = [];

    private static bool $enableConfigByParams = false;
    private static bool $useComponentModelCache = false;
    private static bool $mustNamespaceTypes = false;
    private static bool $useSingleTypeInsteadOfUnionType = false;
    private static bool $enableAdminSchema = false;
    private static bool $validateFieldTypeResponseWithSchemaDefinition = false;
    private static bool $treatTypeCoercingFailuresAsErrors = false;
    private static bool $treatUndefinedFieldOrDirectiveArgsAsErrors = false;
    private static bool $setFailingFieldResponseAsNull = false;
    private static bool $removeFieldIfDirectiveFailed = false;
    private static bool $coerceInputFromSingleValueToList = false;
    private static bool $enableUnionTypeImplementingInterfaceType = false;
    private static bool $enableFieldOrDirectiveArgumentDeprecations = false;
    private static bool $skipExposingDangerouslyDynamicScalarTypeInSchema = false;

    /**
     * Initialize component configuration
     */
    public static function init(): void
    {
        // Allow to override the configuration with values passed in the query string:
        // "config": comma-separated string with all fields with value "true"
        // Whatever fields are not there, will be considered "false"
        self::$overrideConfiguration = array();
        if (self::enableConfigByParams()) {
            self::$overrideConfiguration = isset($_REQUEST[Params::CONFIG]) ? explode(Param::VALUE_SEPARATOR, $_REQUEST[Params::CONFIG]) : array();
        }
    }

    /**
     * Indicate if the configuration is overriden by params
     */
    public static function doingOverrideConfiguration(): bool
    {
        return !empty(self::$overrideConfiguration);
    }

    /**
     * Obtain the override configuration for a key, with possible values being only
     * `true` or `false`, or `null` if that key is not set
     *
     * @param string $key the key to get the value
     */
    public static function getOverrideConfiguration(string $key): ?bool
    {
        // If no values where defined in the configuration, then skip it completely
        if (empty(self::$overrideConfiguration)) {
            return null;
        }

        // Check if the key has been given value "true"
        if (in_array($key, self::$overrideConfiguration)) {
            return true;
        }

        // Otherwise, it has value "false"
        return false;
    }

    /**
     * Access layer to the environment variable, enabling to override its value
     * Indicate if the configuration can be set through params
     */
    public static function enableConfigByParams(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_CONFIG_BY_PARAMS;
        $selfProperty = &self::$enableConfigByParams;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    /**
     * Access layer to the environment variable, enabling to override its value
     * Indicate if to use the cache
     */
    public static function useComponentModelCache(): bool
    {
        // If we are overriding the configuration, then do NOT use the cache
        // Otherwise, parameters from the config have need to be added to $vars, however they can't,
        // since we want the $vars model_instance_id to not change when testing with the "config" param
        if (self::doingOverrideConfiguration()) {
            return false;
        }

        // Define properties
        $envVariable = Environment::USE_COMPONENT_MODEL_CACHE;
        $selfProperty = &self::$useComponentModelCache;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public static function mustNamespaceTypes(): bool
    {
        // Define properties
        $envVariable = Environment::NAMESPACE_TYPES_AND_INTERFACES;
        $selfProperty = &self::$mustNamespaceTypes;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public static function useSingleTypeInsteadOfUnionType(): bool
    {
        // Define properties
        $envVariable = Environment::USE_SINGLE_TYPE_INSTEAD_OF_UNION_TYPE;
        $selfProperty = &self::$useSingleTypeInsteadOfUnionType;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public static function enableAdminSchema(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_ADMIN_SCHEMA;
        $selfProperty = &self::$enableAdminSchema;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    /**
     * By default, validate for DEV only
     */
    public static function validateFieldTypeResponseWithSchemaDefinition(): bool
    {
        // Define properties
        $envVariable = Environment::VALIDATE_FIELD_TYPE_RESPONSE_WITH_SCHEMA_DEFINITION;
        $selfProperty = &self::$validateFieldTypeResponseWithSchemaDefinition;
        $defaultValue = RootEnvironment::isApplicationEnvironmentDev();
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    /**
     * By default, errors produced from casting a type (eg: "3.5 to int")
     * are treated as warnings, not errors
     */
    public static function treatTypeCoercingFailuresAsErrors(): bool
    {
        // Define properties
        $envVariable = Environment::TREAT_TYPE_COERCING_FAILURES_AS_ERRORS;
        $selfProperty = &self::$treatTypeCoercingFailuresAsErrors;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    /**
     * By default, querying for a field or directive argument
     * which has not been defined in the schema
     * is treated as a warning, not an error
     */
    public static function treatUndefinedFieldOrDirectiveArgsAsErrors(): bool
    {
        // Define properties
        $envVariable = Environment::TREAT_UNDEFINED_FIELD_OR_DIRECTIVE_ARGS_AS_ERRORS;
        $selfProperty = &self::$treatUndefinedFieldOrDirectiveArgsAsErrors;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    /**
     * The GraphQL spec indicates that, when a field produces an error (during
     * value resolution or coercion) then its response must be set as null:
     *
     *   If a field error is raised while resolving a field, it is handled as though the field returned null, and the error must be added to the "errors" list in the response.
     *
     * @see https://spec.graphql.org/draft/#sec-Handling-Field-Errors
     */
    public static function setFailingFieldResponseAsNull(): bool
    {
        // Define properties
        $envVariable = Environment::SET_FAILING_FIELD_RESPONSE_AS_NULL;
        $selfProperty = &self::$setFailingFieldResponseAsNull;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    /**
     * Indicate: If a directive fails, then remove the affected IDs/fields from the upcoming stages of the directive pipeline execution
     */
    public static function removeFieldIfDirectiveFailed(): bool
    {
        // Define properties
        $envVariable = Environment::REMOVE_FIELD_IF_DIRECTIVE_FAILED;
        $selfProperty = &self::$removeFieldIfDirectiveFailed;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    /**
     * Support passing a single value where a list is expected.
     * Defined in the GraphQL spec.
     *
     * @see https://spec.graphql.org/draft/#sec-List.Input-Coercion
     */
    public static function coerceInputFromSingleValueToList(): bool
    {
        // Define properties
        $envVariable = Environment::COERCE_INPUT_FROM_SINGLE_VALUE_TO_LIST;
        $selfProperty = &self::$coerceInputFromSingleValueToList;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    /**
     * Support GraphQL RFC "Union types can implement interfaces".
     * It is disabled by default because it can lead to runtime exceptions.
     *
     * @see https://github.com/graphql/graphql-spec/issues/518
     */
    public static function enableUnionTypeImplementingInterfaceType(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_UNION_TYPE_IMPLEMENTING_INTERFACE_TYPE;
        $selfProperty = &self::$enableUnionTypeImplementingInterfaceType;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    /**
     * Deprecations for the field/directive args.
     *
     * Watch out! The GraphQL spec does not include deprecations for arguments,
     * only for fields and enum values, but here it is added nevertheless.
     * This message is shown on runtime when executing a query with a deprecated field,
     * but it's not shown when doing introspection.
     *
     * @see https://spec.graphql.org/draft/#sec-Schema-Introspection.Schema-Introspection-Schema
     */
    public static function enableFieldOrDirectiveArgumentDeprecations(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_FIELD_OR_DIRECTIVE_ARGUMENT_DEPRECATIONS;
        $selfProperty = &self::$enableFieldOrDirectiveArgumentDeprecations;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    /**
     * `DangerouslyDynamic` is a special scalar type which is not coerced or validated.
     * In particular, it does not need to validate if it is an array or not,
     * as according to the applied WrappingType.
     *
     * This behavior is not compatible with the GraphQL spec!
     *
     * For instance, type `DangerouslyDynamic` could have values
     * `"hello"` and `["hello"]`, but in GraphQL we must differentiate
     * these values by types `String` and `[String]`.
     *
     * This config enables to disable this behavior. In this case, all fields,
     * field arguments and directive arguments which use this type will
     * automatically not be added to the schema.
     */
    public static function skipExposingDangerouslyDynamicScalarTypeInSchema(): bool
    {
        // Define properties
        $envVariable = Environment::SKIP_EXPOSING_DANGEROUSLY_DYNAMIC_SCALAR_TYPE_IN_SCHEMA;
        $selfProperty = &self::$skipExposingDangerouslyDynamicScalarTypeInSchema;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }
}
