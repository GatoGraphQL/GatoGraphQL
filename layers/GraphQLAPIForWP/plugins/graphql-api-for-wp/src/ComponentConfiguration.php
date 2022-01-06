<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use PoP\BasicService\Component\AbstractComponentConfiguration;
use PoP\BasicService\Component\EnvironmentValueHelpers;

class ComponentConfiguration extends AbstractComponentConfiguration
{
    // /**
    //  * URL base for the module, pointing to graphql-api.com
    //  */
    // public function getModuleURLBase(): string
    // {
    //     // Define properties
    //     $envVariable = Environment::MODULE_URL_BASE;
    //     $defaultValue = 'https://graphql-api.com/modules/';
    //     // Initialize property from the environment/hook
    //     $this->getConfigurationValueFromEnvVariable(
    //         $envVariable,
    //         $defaultValue
    //     );
    //     return $this->configuration[$envVariable];
    // }

    /**
     * Group the fields under the type when printing it for the user
     */
    public function groupFieldsUnderTypeForPrint(): bool
    {
        // Define properties
        $envVariable = Environment::GROUP_FIELDS_UNDER_TYPE_FOR_PRINT;
        $defaultValue = true;
        $callback = [EnvironmentValueHelpers::class, 'toBool'];

        // Initialize property from the environment/hook
        $this->getConfigurationValueFromEnvVariable(
            $envVariable,
            $defaultValue,
            $callback,
        );
        return $this->configuration[$envVariable];
    }

    /**
     * The label to show when the value is empty
     */
    public function getEmptyLabel(): string
    {
        // Define properties
        $envVariable = Environment::EMPTY_LABEL;
        $defaultValue = \__('---', 'graphql-api');

        // Initialize property from the environment/hook
        $this->getConfigurationValueFromEnvVariable(
            $envVariable,
            $defaultValue,
        );
        return $this->configuration[$envVariable];
    }

    /**
     * The label to show when the value comes from the settings
     */
    public function getSettingsValueLabel(): string
    {
        // Define properties
        $envVariable = Environment::SETTINGS_VALUE_LABEL;
        $defaultValue = \__('🟡 Default', 'graphql-api');

        // Initialize property from the environment/hook
        $this->getConfigurationValueFromEnvVariable(
            $envVariable,
            $defaultValue,
        );
        return $this->configuration[$envVariable];
    }

    /**
     * The slug to use as base when accessing the custom endpoint
     */
    public function getCustomEndpointSlugBase(): string
    {
        // Define properties
        $envVariable = Environment::ENDPOINT_SLUG_BASE;
        $defaultValue = 'graphql';

        // Initialize property from the environment/hook
        $this->getConfigurationValueFromEnvVariable(
            $envVariable,
            $defaultValue,
        );
        return $this->configuration[$envVariable];
    }

    /**
     * The slug to use as base when accessing the persisted query
     */
    public function getPersistedQuerySlugBase(): string
    {
        // Define properties
        $envVariable = Environment::PERSISTED_QUERY_SLUG_BASE;
        $defaultValue = 'graphql-query';

        // Initialize property from the environment/hook
        $this->getConfigurationValueFromEnvVariable(
            $envVariable,
            $defaultValue,
        );
        return $this->configuration[$envVariable];
    }

    /**
     * If `"admin"`, only the admin can compose a GraphQL query and endpoint
     * If `"post"`, the workflow from creating posts is employed (i.e. Author role can create
     * but not publish the query, Editor role can publish it, etc)
     */
    public function getEditingAccessScheme(): ?string
    {
        // Define properties
        $envVariable = Environment::EDITING_ACCESS_SCHEME;
        $defaultValue = null;

        // Initialize property from the environment/hook
        $this->getConfigurationValueFromEnvVariable(
            $envVariable,
            $defaultValue,
        );
        return $this->configuration[$envVariable];
    }
}
