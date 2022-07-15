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
            Environment::USE_COMPONENT_MODEL_CACHE
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

    public function useSingleTypeInsteadOfUnionType(): bool
    {
        $envVariable = Environment::USE_SINGLE_TYPE_INSTEAD_OF_UNION_TYPE;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

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
        $callback = EnvironmentValueHelpers::toBool(...);

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
}
