<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;

class ComponentConfiguration extends \PoP\BasicService\Component\AbstractComponentConfiguration
{
    // private static string $getModuleURLBase;
    private static bool $groupFieldsUnderTypeForPrint = false;
    private static string $getEmptyLabel = '';
    private static string $getSettingsValueLabel = '';
    private static ?string $getCustomEndpointSlugBase = null;
    private static ?string $getPersistedQuerySlugBase = null;
    private static ?string $getEditingAccessScheme = null;

    // /**
    //  * URL base for the module, pointing to graphql-api.com
    //  */
    // public static function getModuleURLBase(): string
    // {
    //     // Define properties
    //     $envVariable = Environment::MODULE_URL_BASE;
    //     $selfProperty = &self::$getModuleURLBase;
    //     $defaultValue = 'https://graphql-api.com/modules/';
    //     // Initialize property from the environment/hook
    //     self::maybeInitializeConfigurationValue(
    //         $envVariable,
    //         $selfProperty,
    //         $defaultValue
    //     );
    //     return $selfProperty;
    // }
    /**
     * Group the fields under the type when printing it for the user
     */
    public static function groupFieldsUnderTypeForPrint(): bool
    {
        // Define properties
        $envVariable = Environment::GROUP_FIELDS_UNDER_TYPE_FOR_PRINT;
        $selfProperty = &self::$groupFieldsUnderTypeForPrint;
        $defaultValue = true;
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
     * The label to show when the value is empty
     */
    public static function getEmptyLabel(): string
    {
        // Define properties
        $envVariable = Environment::EMPTY_LABEL;
        $selfProperty = &self::$getEmptyLabel;
        $defaultValue = \__('---', 'graphql-api');

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue
        );
        return $selfProperty;
    }

    /**
     * The label to show when the value comes from the settings
     */
    public static function getSettingsValueLabel(): string
    {
        // Define properties
        $envVariable = Environment::SETTINGS_VALUE_LABEL;
        $selfProperty = &self::$getSettingsValueLabel;
        $defaultValue = \__('🟡 Default', 'graphql-api');

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue
        );
        return $selfProperty;
    }

    /**
     * The slug to use as base when accessing the custom endpoint
     */
    public static function getCustomEndpointSlugBase(): string
    {
        // Define properties
        $envVariable = Environment::ENDPOINT_SLUG_BASE;
        $selfProperty = &self::$getCustomEndpointSlugBase;
        $defaultValue = 'graphql';

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue
        );
        return $selfProperty;
    }

    /**
     * The slug to use as base when accessing the persisted query
     */
    public static function getPersistedQuerySlugBase(): string
    {
        // Define properties
        $envVariable = Environment::PERSISTED_QUERY_SLUG_BASE;
        $selfProperty = &self::$getPersistedQuerySlugBase;
        $defaultValue = 'graphql-query';

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue
        );
        return $selfProperty;
    }

    /**
     * If `"admin"`, only the admin can compose a GraphQL query and endpoint
     * If `"post"`, the workflow from creating posts is employed (i.e. Author role can create
     * but not publish the query, Editor role can publish it, etc)
     */
    public static function getEditingAccessScheme(): ?string
    {
        // Define properties
        $envVariable = Environment::EDITING_ACCESS_SCHEME;
        $selfProperty = &self::$getEditingAccessScheme;
        $defaultValue = null;

        // Initialize property from the environment/hook
        self::maybeInitializeConfigurationValue(
            $envVariable,
            $selfProperty,
            $defaultValue
        );
        return $selfProperty;
    }
}
