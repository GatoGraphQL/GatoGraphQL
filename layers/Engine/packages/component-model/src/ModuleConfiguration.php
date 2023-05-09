<?php

declare(strict_types=1);

namespace PoP\ComponentModel;

use PoP\Root\App;
use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;
use PoP\Root\Environment as RootEnvironment;
use PoP\Root\Module as RootModule;
use PoP\Root\ModuleConfiguration as RootModuleConfiguration;

class ModuleConfiguration extends AbstractModuleConfiguration
{
    public function enableComponentModelCache(): bool
    {
        $envVariable = Environment::ENABLE_COMPONENT_MODEL_CACHE;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function useComponentModelCache(): bool
    {
        if (!$this->enableComponentModelCache()) {
            return false;
        }

        /**
         * Component Model Cache is currently broken,
         * hence do not enable this functionality.
         *
         * @see https://github.com/leoloso/PoP/issues/1614
         */
        return false;
        /** @phpstan-ignore-next-line */
        $envVariable = Environment::USE_COMPONENT_MODEL_CACHE;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    protected function enableHook(string $envVariable): bool
    {
        return match ($envVariable) {
            Environment::ENABLE_COMPONENT_MODEL_CACHE,
            Environment::USE_COMPONENT_MODEL_CACHE,
            Environment::SUPPORT_DEFINING_SERVICES_IN_THE_CONTAINER_BASED_ON_THE_CONTEXT
                => false,
            default => parent::enableHook($envVariable),
        };
    }

    public function mustNamespaceTypes(): bool
    {
        $envVariable = Environment::NAMESPACE_TYPES_AND_INTERFACES;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function enableMutations(): bool
    {
        $envVariable = Environment::ENABLE_MUTATIONS;
        $defaultValue = true;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function exposeSensitiveDataInSchema(): bool
    {
        $envVariable = Environment::EXPOSE_SENSITIVE_DATA_IN_SCHEMA;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    /**
     * This method could return `true`, which means:
     *
     *   "Do always automatically validate the type
     *   of the result against the type declared in the schema".
     * 
     * However, the great majority of fields already return
     * the proper type as it is the response from the method they
     * call, then there's no need to execute this validation.
     * 
     * For this reason, keep the value in `false`, and have
     * every field that is not sure what it will return
     * (eg: calling App::request(...) or a Guzzle HTTP request)
     * indicate that it must be validated, by defining
     * `validateResolvedFieldType` => `true`
     */
    public function validateFieldTypeResponseWithSchemaDefinition(): bool
    {
        $envVariable = Environment::VALIDATE_FIELD_TYPE_RESPONSE_WITH_SCHEMA_DEFINITION;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    /**
     * Indicate: If a directive fails, then set those fields in `null`
     * and remove the affected IDs/fields from the upcoming stages
     * of the directive pipeline execution.
     */
    public function setFieldAsNullIfDirectiveFailed(): bool
    {
        $envVariable = Environment::SET_FIELD_AS_NULL_IF_DIRECTIVE_FAILED;
        $defaultValue = true;
        $callback = EnvironmentValueHelpers::toBool(...);

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
        $defaultValue = true;
        $callback = EnvironmentValueHelpers::toBool(...);

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
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    /**
     * `DangerouslyNonSpecificScalar` is a special scalar type which is not coerced or validated.
     * In particular, it does not need to validate if it is an array or not,
     * as according to the applied WrappingType.
     *
     * This behavior is not compatible with the GraphQL spec!
     *
     * For instance, type `DangerouslyNonSpecificScalar` could have values
     * `"hello"` and `["hello"]`, but in GraphQL we must differentiate
     * these values by types `String` and `[String]`.
     *
     * This config enables to disable this behavior. In this case, all fields,
     * field arguments and directive arguments which use this type will
     * automatically not be added to the schema.
     */
    public function skipExposingDangerouslyNonSpecificScalarTypeTypeInSchema(): bool
    {
        $envVariable = Environment::SKIP_EXPOSING_DANGEROUSLY_NON_SPECIFIC_SCALAR_TYPE_IN_SCHEMA;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    /**
     * Indicate if users can add URL params that modify the Engine's behavior.
     */
    public function enableModifyingEngineBehaviorViaRequest(): bool
    {
        /** @var RootModuleConfiguration */
        $rootModuleConfiguration = App::getModule(RootModule::class)->getConfiguration();
        if (!$rootModuleConfiguration->enablePassingStateViaRequest()) {
            return false;
        }

        $envVariable = Environment::ENABLE_MODIFYING_ENGINE_BEHAVIOR_VIA_REQUEST;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    /**
     * @return string[]
     */
    public function getEnabledFeedbackCategoryExtensions(): array
    {
        $envVariable = Environment::ENABLE_FEEDBACK_CATEGORY_EXTENSIONS;
        $defaultValue = [];
        $callback = EnvironmentValueHelpers::commaSeparatedStringToArray(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function logExceptionErrorMessagesAndTraces(): bool
    {
        $envVariable = Environment::LOG_EXCEPTION_ERROR_MESSAGES_AND_TRACES;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function sendExceptionErrorMessages(): bool
    {
        $envVariable = Environment::SEND_EXCEPTION_ERROR_MESSAGES;
        $defaultValue = RootEnvironment::isApplicationEnvironmentDev();
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function sendExceptionTraces(): bool
    {
        if (!$this->sendExceptionErrorMessages()) {
            return false;
        }

        $envVariable = Environment::SEND_EXCEPTION_TRACES;
        $defaultValue = RootEnvironment::isApplicationEnvironmentDev();
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function enableSelfField(): bool
    {
        $envVariable = Environment::ENABLE_SELF_FIELD;
        $defaultValue = true;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function enableTypeNameGlobalFields(): bool
    {
        $envVariable = Environment::ENABLE_TYPENAME_GLOBAL_FIELDS;
        $defaultValue = true;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function exposeCoreFunctionalityGlobalFields(): bool
    {
        $envVariable = Environment::EXPOSE_CORE_FUNCTIONALITY_GLOBAL_FIELDS;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function exposeSchemaTypeDirectiveLocations(): bool
    {
        $envVariable = Environment::EXPOSE_SCHEMA_TYPE_DIRECTIVE_LOCATIONS;
        $defaultValue = true;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    /**
     * Indicate if to enable to restrict a field and directive by version,
     * using the same semantic versioning constraint rules used by Composer
     *
     * @see https://getcomposer.org/doc/articles/versions.md Composer's semver constraint rules
     */
    public function enableSemanticVersionConstraints(): bool
    {
        $envVariable = Environment::ENABLE_SEMANTIC_VERSION_CONSTRAINTS;
        $defaultValue = true;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function getClientIPAddressServerPropertyName(): string
    {
        $envVariable = Environment::CLIENT_IP_ADDRESS_SERVER_PROPERTY_NAME;
        $defaultValue = 'REMOTE_ADDR';

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue
        );
    }

    /**
     * Indicate if the Service Container can depend on some value
     * set as context (eg: an env var).
     *
     * For instance, will updating "Nested Mutations" from "Not Enabled"
     * to "Enabled" register some new FieldResolver in the container?
     *
     * When disabled (as it is by default), Services must be registered always
     * in the container, and enabled/disabled on runtime (eg: via method
     * `getFieldNamesToResolve` in the FieldResolvers).
     *
     * This method has been added to be able to reference the
     * corresponding logic throughout the application (and not delete
     * that code), but it is not expected to be set as `true`.
     */
    public function supportDefiningServicesInTheContainerBasedOnTheContext(): bool
    {
        $envVariable = Environment::SUPPORT_DEFINING_SERVICES_IN_THE_CONTAINER_BASED_ON_THE_CONTEXT;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }
}
