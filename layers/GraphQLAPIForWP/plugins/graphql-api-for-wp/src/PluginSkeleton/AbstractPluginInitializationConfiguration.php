<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

use GraphQLAPI\GraphQLAPI\Facades\Registries\SystemModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\StaticHelpers\PluginEnvironmentHelpers;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use PoP\Root\Module\ModuleConfigurationHelpers;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Root\Facades\Instances\SystemInstanceManagerFacade;

/**
 * Base class to set the configuration for all the PoP components,
 * for both the main plugin and extensions.
 *
 * To set the value for properties, it uses this order:
 *
 * 1. Retrieve it as an environment value, if defined
 * 2. Retrieve as a constant `GRAPHQL_API_...` from wp-config.php, if defined
 * 3. Retrieve it from the user settings, if stored
 * 4. Use the default value
 *
 * If a slug is set or updated in the environment variable or wp-config constant,
 * it is necessary to flush the rewrite rules for the change to take effect.
 * For that, on the WordPress admin,
 * go to Settings => Permalinks and click on Save changes.
 */
abstract class AbstractPluginInitializationConfiguration implements PluginInitializationConfigurationInterface
{
    /**
     * Initialize all configuration
     */
    public function initialize(): void
    {
        $this->mapEnvVariablesToWPConfigConstants();
        $this->defineEnvironmentConstantsFromSettings();
        $this->defineEnvironmentConstantsFromCallbacks();
    }

    /**
     * Map the environment variables from the components, to WordPress wp-config.php constants
     */
    protected function mapEnvVariablesToWPConfigConstants(): void
    {
        // All the environment variables to override
        $mappings = $this->getEnvVariablesToWPConfigConstantsMapping();

        // For each environment variable, see if it has been defined as a wp-config.php constant
        foreach ($mappings as $mapping) {
            $class = $mapping['class'];
            $envVariable = $mapping['envVariable'];

            // If the environment value has been defined, then do nothing, since it has priority
            if (getenv($envVariable) !== false) {
                continue;
            }
            $hookName = ModuleConfigurationHelpers::getHookName(
                $class,
                $envVariable
            );

            \add_filter(
                $hookName,
                /**
                 * Override the value of an environment variable if it has been definedas a constant
                 * in wp-config.php, with the environment name prepended with "GRAPHQL_API_"
                 */
                function ($value) use ($envVariable) {
                    if (PluginEnvironmentHelpers::isWPConfigConstantDefined($envVariable)) {
                        return PluginEnvironmentHelpers::getWPConfigConstantValue($envVariable);
                    }
                    return $value;
                }
            );
        }
    }

    /**
     * All the environment variables to override
     */
    protected function getEnvVariablesToWPConfigConstantsMapping(): array
    {
        return [];
    }

    /**
     * Define the values for certain environment constants from the plugin settings
     */
    protected function defineEnvironmentConstantsFromSettings(): void
    {
        $moduleRegistry = SystemModuleRegistryFacade::getInstance();

        // All the environment variables to override
        $mappings = $this->getEnvironmentConstantsFromSettingsMapping();

        // For each environment variable, see if its value has been saved in the settings
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        foreach ($mappings as $mapping) {
            $module = $mapping['module'];
            $condition = $mapping['condition'] ?? true;
            // Check if the hook must be executed always (condition => 'any') or with
            // stated enabled (true) or disabled (false). By default, it's enabled
            if ($condition !== 'any' && $condition !== $moduleRegistry->isModuleEnabled($module)) {
                continue;
            }
            // If the environment value has been defined, or the constant in wp-config.php,
            // then do nothing, since they have priority
            $envVariable = $mapping['envVariable'];
            if (getenv($envVariable) !== false || PluginEnvironmentHelpers::isWPConfigConstantDefined($envVariable)) {
                continue;
            }
            $hookName = ModuleConfigurationHelpers::getHookName(
                $mapping['class'],
                $envVariable
            );
            $option = $mapping['option'];
            $optionModule = $mapping['optionModule'] ?? $module;
            // Make explicit it can be null so that PHPStan level 3 doesn't fail
            $callback = $mapping['callback'] ?? null;
            \add_filter(
                $hookName,
                function () use ($userSettingsManager, $optionModule, $option, $callback) {
                    $value = $userSettingsManager->getSetting($optionModule, $option);
                    if ($callback !== null) {
                        return $callback($value);
                    }
                    return $value;
                }
            );
        }
    }

    /**
     * Define the values for certain environment constants from the plugin settings
     */
    protected function getEnvironmentConstantsFromSettingsMapping(): array
    {
        return [];
    }

    /**
     * Define the values for certain environment constants from the plugin settings
     */
    protected function defineEnvironmentConstantsFromCallbacks(): void
    {
        $mappings = $this->getEnvironmentConstantsFromCallbacksMapping();
        foreach ($mappings as $mapping) {
            // If the environment value has been defined, or the constant in wp-config.php,
            // then do nothing, since they have priority
            $envVariable = $mapping['envVariable'];
            if (getenv($envVariable) !== false || PluginEnvironmentHelpers::isWPConfigConstantDefined($envVariable)) {
                continue;
            }
            $hookName = ModuleConfigurationHelpers::getHookName(
                $mapping['class'],
                $envVariable
            );
            $callback = $mapping['callback'];
            \add_filter(
                $hookName,
                fn () => $callback(),
            );
        }
    }

    /**
     * Define the values for certain environment constants from the plugin settings
     */
    protected function getEnvironmentConstantsFromCallbacksMapping(): array
    {
        return [];
    }

    /**
     * Provide the configuration for all components required in the plugin
     *
     * @return array<string, array> [key]: Module class, [value]: Configuration
     */
    public function getModuleClassConfiguration(): array
    {
        return array_merge_recursive(
            $this->getPredefinedModuleClassConfiguration(),
            $this->getBasedOnModuleEnabledStateModuleClassConfiguration(),
        );
    }

    /**
     * Get the fixed configuration for all components required in the plugin
     *
     * @return array<string, array> [key]: Module class, [value]: Configuration
     */
    protected function getPredefinedModuleClassConfiguration(): array
    {
        return [];
    }

    /**
     * Add configuration values if modules are enabled or disabled
     *
     * @return array<string, array> $moduleClassConfiguration [key]: Module class, [value]: Configuration
     */
    protected function getBasedOnModuleEnabledStateModuleClassConfiguration(): array
    {
        $moduleRegistry = SystemModuleRegistryFacade::getInstance();
        $moduleClassConfiguration = [];

        $moduleToModuleClassConfigurationMappings = $this->getModuleToModuleClassConfigurationMapping();
        foreach ($moduleToModuleClassConfigurationMappings as $mapping) {
            // Copy the state (enabled/disabled) to the module configuration
            $value = $moduleRegistry->isModuleEnabled($mapping['module']);
            // Make explicit it can be null so that PHPStan level 3 doesn't fail
            $callback = $mapping['callback'] ?? null;
            if ($callback !== null) {
                $value = $callback($value);
            }
            $moduleClassConfiguration[$mapping['class']][$mapping['envVariable']] = $value;
        }

        return $moduleClassConfiguration;
    }

    protected function getModuleToModuleClassConfigurationMapping(): array
    {
        return [];
    }

    /**
     * Add schema Module classes to skip initializing
     *
     * @return string[] List of `Module` class which must not initialize their Schema services
     */
    public function getSchemaModuleClassesToSkip(): array
    {
        // If doing ?behavior=unrestricted, always enable all schema-type modules
        $systemInstanceManager = SystemInstanceManagerFacade::getInstance();
        /** @var EndpointHelpers */
        $endpointHelpers = $systemInstanceManager->getInstance(EndpointHelpers::class);
        if ($endpointHelpers->isRequestingAdminFixedSchemaGraphQLEndpoint()) {
            return [];
        }

        // Module classes are skipped if the module is disabled
        $moduleRegistry = SystemModuleRegistryFacade::getInstance();
        $skipSchemaModuleClassesPerModule = array_filter(
            $this->getModuleClassesToSkipIfModuleDisabled(),
            fn ($module) => !$moduleRegistry->isModuleEnabled($module),
            ARRAY_FILTER_USE_KEY
        );
        return GeneralUtils::arrayFlatten(array_values(
            $skipSchemaModuleClassesPerModule
        ));
    }

    /**
     * Provide the list of modules to check if they are enabled and,
     * if they are not, what component classes must skip initialization
     *
     * @return array<string,string[]>
     */
    protected function getModuleClassesToSkipIfModuleDisabled(): array
    {
        return [];
    }
}
