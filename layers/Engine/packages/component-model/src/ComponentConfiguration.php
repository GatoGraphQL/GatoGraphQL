<?php

declare(strict_types=1);

namespace PoP\ComponentModel;

use PoP\ComponentModel\Constants\Params;
use PoP\ComponentModel\Tokens\Param;
use PoP\ComponentModel\ComponentConfiguration\EnvironmentValueHelpers;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationTrait;
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
    private static bool $enableSchemaEntityRegistries = false;
    private static bool $namespaceTypesAndInterfaces = false;
    private static bool $useSingleTypeInsteadOfUnionType = false;
    private static bool $enableAdminSchema = false;
    private static bool $validateFieldTypeResponseWithSchemaDefinition = false;

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
     * @param $key the key to get the value
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

    /**
     * Access layer to the environment variable, enabling to override its value
     * Indicate if to keep the several entities that make up a schema (types, directives) in a registry
     * This functionality is not used by PoP itself, hence it defaults to `false`
     * It can be used by making a mapping from type name to type resolver class, as to reference a type
     * by a name, if needed (eg: to save in the application's configuration)
     */
    public static function enableSchemaEntityRegistries(): bool
    {
        // Define properties
        $envVariable = Environment::ENABLE_SCHEMA_ENTITY_REGISTRIES;
        $selfProperty = &self::$enableSchemaEntityRegistries;
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

    public static function namespaceTypesAndInterfaces(): bool
    {
        // Define properties
        $envVariable = Environment::NAMESPACE_TYPES_AND_INTERFACES;
        $selfProperty = &self::$namespaceTypesAndInterfaces;
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
}
