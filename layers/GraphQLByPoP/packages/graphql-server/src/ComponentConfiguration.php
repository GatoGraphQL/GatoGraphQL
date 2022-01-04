<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\Root\Managers\ComponentManager;
use PoP\API\Component as APIComponent;
use PoP\API\ComponentConfiguration as APIComponentConfiguration;
use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    private bool $exposeSelfFieldForRootTypeInGraphQLSchema = false;
    private bool $sortGraphQLSchemaAlphabetically = true;
    private bool $enableProactiveFeedback = true;
    private bool $enableProactiveFeedbackDeprecations = true;
    private bool $enableProactiveFeedbackNotices = true;
    private bool $enableProactiveFeedbackTraces = true;
    private bool $enableProactiveFeedbackLogs = true;
    private bool $enableNestedMutations = false;
    private ?bool $enableGraphQLIntrospection = null;
    private bool $exposeSelfFieldInGraphQLSchema = false;
    private bool $addFullSchemaFieldToGraphQLSchema = false;
    private bool $addVersionToGraphQLSchemaFieldDescription = false;
    private bool $enableSettingMutationSchemeByURLParam = false;
    private bool $enableEnablingGraphQLIntrospectionByURLParam = false;
    private bool $addGraphQLIntrospectionPersistedQuery = false;
    private bool $addConnectionFromRootToQueryRootAndMutationRoot = false;
    private bool $exposeSchemaIntrospectionFieldInSchema = false;
    private bool $exposeGlobalFieldsInGraphQLSchema = false;

    public function exposeSelfFieldForRootTypeInGraphQLSchema(): bool
    {
        // Define properties
        $envVariable = Environment::EXPOSE_SELF_FIELD_FOR_ROOT_TYPE_IN_GRAPHQL_SCHEMA;
        $selfProperty = &$this->exposeSelfFieldForRootTypeInGraphQLSchema;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function sortGraphQLSchemaAlphabetically(): bool
    {
        // Define properties
        $envVariable = Environment::SORT_GRAPHQL_SCHEMA_ALPHABETICALLY;
        $selfProperty = &$this->sortGraphQLSchemaAlphabetically;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function enableProactiveFeedback(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_PROACTIVE_FEEDBACK;
        $selfProperty = &$this->enableProactiveFeedback;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function enableProactiveFeedbackDeprecations(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_PROACTIVE_FEEDBACK_DEPRECATIONS;
        $selfProperty = &$this->enableProactiveFeedbackDeprecations;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function enableProactiveFeedbackNotices(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_PROACTIVE_FEEDBACK_NOTICES;
        $selfProperty = &$this->enableProactiveFeedbackNotices;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function enableProactiveFeedbackTraces(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_PROACTIVE_FEEDBACK_TRACES;
        $selfProperty = &$this->enableProactiveFeedbackTraces;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function enableProactiveFeedbackLogs(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_PROACTIVE_FEEDBACK_LOGS;
        $selfProperty = &$this->enableProactiveFeedbackLogs;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function enableNestedMutations(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_NESTED_MUTATIONS;
        $selfProperty = &$this->enableNestedMutations;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function enableGraphQLIntrospection(): ?bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_GRAPHQL_INTROSPECTION;
        $selfProperty = &$this->enableGraphQLIntrospection;
        $defaultValue = null;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function exposeSelfFieldInGraphQLSchema(): bool
    {
        // Define properties
        $envVariable = Environment::EXPOSE_SELF_FIELD_IN_GRAPHQL_SCHEMA;
        $selfProperty = &$this->exposeSelfFieldInGraphQLSchema;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function addFullSchemaFieldToGraphQLSchema(): bool
    {
        // Define properties
        $envVariable = Environment::ADD_FULLSCHEMA_FIELD_TO_GRAPHQL_SCHEMA;
        $selfProperty = &$this->addFullSchemaFieldToGraphQLSchema;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function addVersionToGraphQLSchemaFieldDescription(): bool
    {
        // Define properties
        $envVariable = Environment::ADD_VERSION_TO_GRAPHQL_SCHEMA_FIELD_DESCRIPTION;
        $selfProperty = &$this->addVersionToGraphQLSchemaFieldDescription;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function enableSettingMutationSchemeByURLParam(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_SETTING_MUTATION_SCHEME_BY_URL_PARAM;
        $selfProperty = &$this->enableSettingMutationSchemeByURLParam;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function enableEnablingGraphQLIntrospectionByURLParam(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_ENABLING_GRAPHQL_INTROSPECTION_BY_URL_PARAM;
        $selfProperty = &$this->enableEnablingGraphQLIntrospectionByURLParam;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function addGraphQLIntrospectionPersistedQuery(): bool
    {
        // Define properties
        $envVariable = Environment::ADD_GRAPHQL_INTROSPECTION_PERSISTED_QUERY;
        $selfProperty = &$this->addGraphQLIntrospectionPersistedQuery;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function addConnectionFromRootToQueryRootAndMutationRoot(): bool
    {
        // Define properties
        $envVariable = Environment::ADD_CONNECTION_FROM_ROOT_TO_QUERYROOT_AND_MUTATIONROOT;
        $selfProperty = &$this->addConnectionFromRootToQueryRootAndMutationRoot;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function exposeSchemaIntrospectionFieldInSchema(): bool
    {
        // Define properties
        $envVariable = Environment::EXPOSE_SCHEMA_INTROSPECTION_FIELD_IN_SCHEMA;
        $selfProperty = &$this->exposeSchemaIntrospectionFieldInSchema;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }

    public function exposeGlobalFieldsInGraphQLSchema(): bool
    {
        /** @var APIComponentConfiguration */
        $componentConfiguration = ComponentManager::getComponent(APIComponent::class)->getConfiguration();
        if ($componentConfiguration->skipExposingGlobalFieldsInFullSchema()) {
            return false;
        }

        // Define properties
        $envVariable = Environment::EXPOSE_GLOBAL_FIELDS_IN_GRAPHQL_SCHEMA;
        $selfProperty = &$this->exposeGlobalFieldsInGraphQLSchema;
        $defaultValue = false;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue,
            $callback
        );
        return $selfProperty;
    }
}
