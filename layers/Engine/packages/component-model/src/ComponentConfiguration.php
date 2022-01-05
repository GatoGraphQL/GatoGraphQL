<?php

declare(strict_types=1);

namespace PoP\ComponentModel;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;
use PoP\ComponentModel\Constants\Params;
use PoP\ComponentModel\Tokens\Param;
use PoP\Root\Environment as RootEnvironment;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    /**
     * Map with the configuration passed by params
     *
     * @var array
     */
    private array $overrideConfiguration = [];

    private bool $enableConfigByParams = false;
    private bool $useComponentModelCache = false;
    private bool $mustNamespaceTypes = false;
    private bool $useSingleTypeInsteadOfUnionType = false;
    private bool $enableAdminSchema = false;
    private bool $validateFieldTypeResponseWithSchemaDefinition = false;
    private bool $treatTypeCoercingFailuresAsErrors = false;
    private bool $treatUndefinedFieldOrDirectiveArgsAsErrors = false;
    private bool $setFailingFieldResponseAsNull = false;
    private bool $removeFieldIfDirectiveFailed = false;
    private bool $convertInputValueFromSingleToList = false;
    private bool $enableUnionTypeImplementingInterfaceType = false;
    private bool $skipExposingDangerouslyDynamicScalarTypeInSchema = false;

    /**
     * Initialize component configuration
     */
    public function init(): void
    {
        // Allow to override the configuration with values passed in the query string:
        // "config": comma-separated string with all fields with value "true"
        // Whatever fields are not there, will be considered "false"
        $this->overrideConfiguration = array();
        if ($this->enableConfigByParams()) {
            $this->overrideConfiguration = isset($_REQUEST[Params::CONFIG]) ? explode(Param::VALUE_SEPARATOR, $_REQUEST[Params::CONFIG]) : array();
        }
    }

    /**
     * Indicate if the configuration is overriden by params
     */
    public function doingOverrideConfiguration(): bool
    {
        return !empty($this->overrideConfiguration);
    }

    /**
     * Obtain the override configuration for a key, with possible values being only
     * `true` or `false`, or `null` if that key is not set
     *
     * @param string $key the key to get the value
     */
    public function getOverrideConfiguration(string $key): ?bool
    {
        // If no values where defined in the configuration, then skip it completely
        if (empty($this->overrideConfiguration)) {
            return null;
        }

        // Check if the key has been given value "true"
        if (in_array($key, $this->overrideConfiguration)) {
            return true;
        }

        // Otherwise, it has value "false"
        return false;
    }

    /**
     * Access layer to the environment variable, enabling to override its value
     * Indicate if the configuration can be set through params
     */
    public function enableConfigByParams(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_CONFIG_BY_PARAMS;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    /**
     * Access layer to the environment variable, enabling to override its value
     * Indicate if to use the cache
     */
    public function useComponentModelCache(): bool
    {
        // If we are overriding the configuration, then do NOT use the cache
        // Otherwise, parameters from the config have need to be added to $vars, however they can't,
        // since we want the $vars model_instance_id to not change when testing with the "config" param
        if ($this->doingOverrideConfiguration()) {
            return false;
        }

        // Define properties
        $envVariable = Environment::USE_COMPONENT_MODEL_CACHE;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    public function mustNamespaceTypes(): bool
    {
        // Define properties
        $envVariable = Environment::NAMESPACE_TYPES_AND_INTERFACES;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    public function useSingleTypeInsteadOfUnionType(): bool
    {
        // Define properties
        $envVariable = Environment::USE_SINGLE_TYPE_INSTEAD_OF_UNION_TYPE;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    public function enableAdminSchema(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_ADMIN_SCHEMA;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    /**
     * By default, validate for DEV only
     */
    public function validateFieldTypeResponseWithSchemaDefinition(): bool
    {
        // Define properties
        $envVariable = Environment::VALIDATE_FIELD_TYPE_RESPONSE_WITH_SCHEMA_DEFINITION;
        $defaultValue = RootEnvironment::isApplicationEnvironmentDev();
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    /**
     * By default, errors produced from casting a type (eg: "3.5 to int")
     * are treated as warnings, not errors
     */
    public function treatTypeCoercingFailuresAsErrors(): bool
    {
        // Define properties
        $envVariable = Environment::TREAT_TYPE_COERCING_FAILURES_AS_ERRORS;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    /**
     * By default, querying for a field or directive argument
     * which has not been defined in the schema
     * is treated as a warning, not an error
     */
    public function treatUndefinedFieldOrDirectiveArgsAsErrors(): bool
    {
        // Define properties
        $envVariable = Environment::TREAT_UNDEFINED_FIELD_OR_DIRECTIVE_ARGS_AS_ERRORS;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    /**
     * The GraphQL spec indicates that, when a field produces an error (during
     * value resolution or coercion) then its response must be set as null:
     *
     *   If a field error is raised while resolving a field, it is handled as though the field returned null, and the error must be added to the "errors" list in the response.
     *
     * @see https://spec.graphql.org/draft/#sec-Handling-Field-Errors
     */
    public function setFailingFieldResponseAsNull(): bool
    {
        // Define properties
        $envVariable = Environment::SET_FAILING_FIELD_RESPONSE_AS_NULL;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    /**
     * Indicate: If a directive fails, then remove the affected IDs/fields from the upcoming stages of the directive pipeline execution
     */
    public function removeFieldIfDirectiveFailed(): bool
    {
        // Define properties
        $envVariable = Environment::REMOVE_FIELD_IF_DIRECTIVE_FAILED;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    /**
     * Support passing a single value where a list is expected.
     * Defined in the GraphQL spec.
     *
     * @see https://spec.graphql.org/draft/#sec-List.Input-Coercion
     */
    public function convertInputValueFromSingleToList(): bool
    {
        // Define properties
        $envVariable = Environment::CONVERT_INPUT_VALUE_FROM_SINGLE_TO_LIST;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    /**
     * Support GraphQL RFC "Union types can implement interfaces".
     * It is disabled by default because it can lead to runtime exceptions.
     *
     * @see https://github.com/graphql/graphql-spec/issues/518
     */
    public function enableUnionTypeImplementingInterfaceType(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_UNION_TYPE_IMPLEMENTING_INTERFACE_TYPE;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
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
    public function skipExposingDangerouslyDynamicScalarTypeInSchema(): bool
    {
        // Define properties
        $envVariable = Environment::SKIP_EXPOSING_DANGEROUSLY_DYNAMIC_SCALAR_TYPE_IN_SCHEMA;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }
}
