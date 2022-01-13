<?php

declare(strict_types=1);

namespace PoP\ComponentModel;

use PoP\Root\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;
use PoP\Root\Environment as RootEnvironment;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    /**
     * Access layer to the environment variable, enabling to override its value
     * Indicate if to use the cache
     */
    public function useComponentModelCache(): bool
    {
        $envVariable = Environment::USE_COMPONENT_MODEL_CACHE;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    protected function enableHook(string $envVariable): bool
    {
        return match ($envVariable) {
            Environment::USE_COMPONENT_MODEL_CACHE => false,
            default => parent::enableHook($envVariable),
        };
    }

    public function mustNamespaceTypes(): bool
    {
        $envVariable = Environment::NAMESPACE_TYPES_AND_INTERFACES;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function useSingleTypeInsteadOfUnionType(): bool
    {
        $envVariable = Environment::USE_SINGLE_TYPE_INSTEAD_OF_UNION_TYPE;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function enableAdminSchema(): bool
    {
        $envVariable = Environment::ENABLE_ADMIN_SCHEMA;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    /**
     * By default, validate for DEV only
     */
    public function validateFieldTypeResponseWithSchemaDefinition(): bool
    {
        $envVariable = Environment::VALIDATE_FIELD_TYPE_RESPONSE_WITH_SCHEMA_DEFINITION;
        $defaultValue = RootEnvironment::isApplicationEnvironmentDev();
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    /**
     * By default, errors produced from casting a type (eg: "3.5 to int")
     * are treated as warnings, not errors
     */
    public function treatTypeCoercingFailuresAsErrors(): bool
    {
        $envVariable = Environment::TREAT_TYPE_COERCING_FAILURES_AS_ERRORS;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    /**
     * By default, querying for a field or directive argument
     * which has not been defined in the schema
     * is treated as a warning, not an error
     */
    public function treatUndefinedFieldOrDirectiveArgsAsErrors(): bool
    {
        $envVariable = Environment::TREAT_UNDEFINED_FIELD_OR_DIRECTIVE_ARGS_AS_ERRORS;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
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
        $envVariable = Environment::SET_FAILING_FIELD_RESPONSE_AS_NULL;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    /**
     * Indicate: If a directive fails, then remove the affected IDs/fields from the upcoming stages of the directive pipeline execution
     */
    public function removeFieldIfDirectiveFailed(): bool
    {
        $envVariable = Environment::REMOVE_FIELD_IF_DIRECTIVE_FAILED;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    /**
     * Support passing a single value where a list is expected.
     * Defined in the GraphQL spec.
     *
     * @see https://spec.graphql.org/draft/#sec-List.Input-Coercion
     */
    public function convertInputValueFromSingleToList(): bool
    {
        $envVariable = Environment::CONVERT_INPUT_VALUE_FROM_SINGLE_TO_LIST;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    /**
     * Support GraphQL RFC "Union types can implement interfaces".
     * It is disabled by default because it can lead to runtime exceptions.
     *
     * @see https://github.com/graphql/graphql-spec/issues/518
     */
    public function enableUnionTypeImplementingInterfaceType(): bool
    {
        $envVariable = Environment::ENABLE_UNION_TYPE_IMPLEMENTING_INTERFACE_TYPE;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
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
        $envVariable = Environment::SKIP_EXPOSING_DANGEROUSLY_DYNAMIC_SCALAR_TYPE_IN_SCHEMA;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    /**
     * Indicate if users can add URL params that modify the Engine's behavior.
     */
    public function enableModifyingEngineBehaviorViaRequestParams(): bool
    {
        $envVariable = Environment::ENABLE_MODIFYING_ENGINE_BEHAVIOR_VIA_REQUEST_PARAMS;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
