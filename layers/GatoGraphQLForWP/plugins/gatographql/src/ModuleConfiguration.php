<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL;

use PoP\Root\Module\AbstractModuleConfiguration;
use PoP\Root\Module\EnvironmentValueHelpers;

class ModuleConfiguration extends AbstractModuleConfiguration
{
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
        $defaultValue = \__('(none selected)', 'gatographql');

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
        $defaultValue = \__('🟡 Default', 'gatographql');

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

    public function getGatoGraphQLWebsiteURL(): string
    {
        $envVariable = Environment::GATOGRAPHQL_WEBSITE_URL;
        $defaultValue = 'https://gatographql.com';

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
    }

    public function getGatoGraphQLBundlesPageURL(): string
    {
        $envVariable = Environment::GATOGRAPHQL_BUNDLES_PAGE_URL;
        $defaultValue = PluginStaticModuleConfiguration::displayGatoGraphQLPROExtensionsOnExtensionsPage()
            ? 'https://gatographql.com/bundles'
            : 'https://gatographql.com/extensions';

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
    }

    public function getGatoGraphQLExtensionsPageURL(): string
    {
        $envVariable = Environment::GATOGRAPHQL_EXTENSIONS_PAGE_URL;
        $defaultValue = 'https://gatographql.com/extensions';

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
    }

    public function getGatoGraphQLExtensionsReferencePageURL(): string
    {
        $envVariable = Environment::GATOGRAPHQL_EXTENSIONS_PAGE_URL;
        $defaultValue = 'https://gatographql.com/extensions-reference';

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
        );
    }

    public function getGatoGraphQLRequestExtensionPageURL(): string
    {
        $envVariable = Environment::GATOGRAPHQL_REQUEST_EXTENSION_PAGE_URL;
        $defaultValue = 'https://gatographql.com/contact/';

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

    public function enableSchemaTutorialPage(): bool
    {
        $envVariable = Environment::ENABLE_SCHEMA_TUTORIAL_PAGE;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function enableLogs(): bool
    {
        $envVariable = Environment::ENABLE_LOGS;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function installPluginSetupData(): bool
    {
        $envVariable = Environment::INSTALL_PLUGIN_SETUP_DATA;
        $defaultValue = true;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function showBundlesContainingReferencedExtensionsOnTutorial(): bool
    {
        $envVariable = Environment::SHOW_BUNDLES_CONTAINING_REFERENCED_EXTENSIONS_ON_TUTORIAL;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function isSchemaConfigurationModuleEnabledByDefault(): bool
    {
        $envVariable = Environment::IS_SCHEMA_CONFIGURATION_ENABLED_BY_DEFAULT;
        $defaultValue = false;
        $callback = EnvironmentValueHelpers::toBool(...);

        return $this->retrieveConfigurationValueOrUseDefault(
            $envVariable,
            $defaultValue,
            $callback,
        );
    }

    public function displayEnableLogsSettingsOption(): bool
    {
        $envVariable = Environment::DISPLAY_ENABLE_LOGS_SETTINGS_OPTION;
        $defaultValue = false;
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
            Environment::GATOGRAPHQL_WEBSITE_URL,
            Environment::GATOGRAPHQL_EXTENSIONS_PAGE_URL,
            Environment::GATOGRAPHQL_REQUEST_EXTENSION_PAGE_URL,
            Environment::USE_SCHEMA_CONFIGURATION_IN_INTERNAL_GRAPHQL_SERVER
                => false,
            default
                => parent::enableHook($envVariable),
        };
    }
}
