<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;

class ModuleConfiguration extends AbstractModuleConfiguration
{
    // /**
    //  * URL base for the module, pointing to gatographql.com
    //  */
    // public function getModuleURLBase(): string
    // {
    //     // Define properties
    //     $envVariable = Environment::MODULE_URL_BASE;
    //     $defaultValue = 'https://gatographql.com/modules/';
    //     // Initialize property from the environment/hook
    //     $this->retrieveConfigurationValueOrUseDefault(
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
        $envVariable = Environment::GROUP_FIELDS_UNDER_TYPE_FOR_PRINT;
        $defaultValue = true;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    /**
     * The label to show when the value is empty
     */
    public function getNoItemsSelectedLabel(): string
    {
        $envVariable = Environment::NO_ITEMS_SELECTED_LABEL;
        $defaultValue = \__('(none selected)', 'gato-graphql');

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
    }

    /**
     * The label to show when the value comes from the settings
     */
    public function getSettingsValueLabel(): string
    {
        $envVariable = Environment::SETTINGS_VALUE_LABEL;
        $defaultValue = \__('🟡 Default', 'gato-graphql');

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
    }

    /**
     * The slug to use as base when accessing the custom endpoint
     */
    public function getCustomEndpointSlugBase(): string
    {
        $envVariable = Environment::ENDPOINT_SLUG_BASE;
        $defaultValue = 'graphql';

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
    }

    /**
     * The slug to use as base when accessing the persisted query
     */
    public function getPersistedQuerySlugBase(): string
    {
        $envVariable = Environment::PERSISTED_QUERY_SLUG_BASE;
        $defaultValue = 'graphql-query';

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
    }

    /**
     * If `"admin"`, only the admin can compose a GraphQL query and endpoint
     * If `"post"`, the workflow from creating posts is employed (i.e. Author role can create
     * but not publish the query, Editor role can publish it, etc)
     */
    public function getEditingAccessScheme(): ?string
    {
        $envVariable = Environment::EDITING_ACCESS_SCHEME;
        $defaultValue = null;

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
    }

    public function enableSettingClientIPAddressServerPropertyName(): bool
    {
        $envVariable = Environment::ENABLE_SETTING_CLIENT_IP_ADDRESS_SERVER_PROPERTY_NAME;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function displayPROPluginInformationInMainPlugin(): bool
    {
        $envVariable = Environment::DISPLAY_PRO_PLUGIN_INFORMATION_IN_MAIN_PLUGIN;
        $defaultValue = true;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    /**
     * @todo Change the URL to the final one
     */
    public function getGatoGraphQLWebsiteURL(): string
    {
        $envVariable = Environment::GATO_GRAPHQL_WEBSITE_URL;
        $defaultValue = 'https://gatographql.com';

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
    }

    /**
     * @todo Change the URL to the final one
     */
    public function getPROPluginWebsiteURL(): string
    {
        $envVariable = Environment::PRO_PLUGIN_WEBSITE_URL;
        $defaultValue = 'https://gatographql.com';

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
    }

    /**
     * @todo Change the URL to the final one
     */
    public function getPROPluginShopURL(): string
    {
        $envVariable = Environment::PRO_PLUGIN_SHOP_URL;
        $defaultValue = 'https://shop.gatographql.com';

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
    }

    /**
     * @todo Change the URL to the final one
     */
    public function getGatoGraphQLRequestExtensionPageURL(): string
    {
        $envVariable = Environment::GATO_GRAPHQL_REQUEST_EXTENSION_PAGE_URL;
        $defaultValue = 'https://gatographql.com/request-extension';

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
    }

    /**
     * This function is not expected to be configured,
     * but it's mainly to help identify all related
     * functionality.
     */
    public function useSchemaConfigurationInInternalGraphQLServer(): bool
    {
        $envVariable = Environment::USE_SCHEMA_CONFIGURATION_IN_INTERNAL_GRAPHQL_SERVER;
        $defaultValue = true;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    /**
     * These values are pre-defined.
     */
    protected function enableHook(string $envVariable): bool
    {
        return match ($envVariable) {
            Environment::DISPLAY_PRO_PLUGIN_INFORMATION_IN_MAIN_PLUGIN,
            Environment::PRO_PLUGIN_WEBSITE_URL,
            Environment::USE_SCHEMA_CONFIGURATION_IN_INTERNAL_GRAPHQL_SERVER
                => false,
            default
                => parent::enableHook($envVariable),
        };
    }
}
