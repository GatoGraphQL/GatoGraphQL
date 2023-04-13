<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

use GraphQLAPI\GraphQLAPI\Constants\HookNames;
use GraphQLAPI\GraphQLAPI\Facades\Registries\SystemModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\PluginGeneralSettingsFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use GraphQLAPI\GraphQLAPI\StaticHelpers\PluginEnvironmentHelpers;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Root\Facades\Instances\SystemInstanceManagerFacade;
use PoP\Root\Module\ModuleConfigurationHelpers;
use PoP\Root\Module\ModuleInterface;

use function apply_filters;
use function is_admin;

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
            /** @var class-string<ModuleInterface> */
            $class = $mapping['class'];
            /** @var string */
            $envVariable = $mapping['envVariable'];

            // If the environment value has been defined, then do nothing, since it has priority
            if (getenv($envVariable) !== false) {
                continue;
            }
            $hookName = ModuleConfigurationHelpers::getHookName(
                (string)$class,
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
     * @return array<int,array{class: class-string<ModuleInterface>, envVariable: string}>
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
            /** @var string */
            $module = $mapping['module'];
            /** @var bool */
            $condition = $mapping['condition'] ?? true;
            // Check if the hook must be executed always (condition => 'any') or with
            // stated enabled (true) or disabled (false). By default, it's enabled
            if ($condition !== 'any' && $condition !== $moduleRegistry->isModuleEnabled($module)) {
                continue;
            }
            // If the environment value has been defined, or the constant in wp-config.php,
            // then do nothing, since they have priority
            /** @var string */
            $envVariable = $mapping['envVariable'];
            if (getenv($envVariable) !== false || PluginEnvironmentHelpers::isWPConfigConstantDefined($envVariable)) {
                continue;
            }
            /** @var class-string<ModuleInterface> */
            $class = $mapping['class'];
            $hookName = ModuleConfigurationHelpers::getHookName(
                (string)$class,
                $envVariable
            );
            /** @var string */
            $option = $mapping['option'];
            /** @var string */
            $optionModule = $mapping['optionModule'] ?? $module;
            // Make explicit it can be null so that PHPStan level 3 doesn't fail
            /** @var callable|null */
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
     *
     * @return array<array<string,mixed>>
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
            /** @var string */
            $envVariable = $mapping['envVariable'];
            if (getenv($envVariable) !== false || PluginEnvironmentHelpers::isWPConfigConstantDefined($envVariable)) {
                continue;
            }
            /** @var class-string<ModuleInterface> */
            $class = $mapping['class'];
            $hookName = ModuleConfigurationHelpers::getHookName(
                (string)$class,
                $envVariable
            );
            /** @var callable */
            $callback = $mapping['callback'];
            \add_filter(
                $hookName,
                fn () => $callback(),
            );
        }
    }

    /**
     * Define the values for certain environment constants from the plugin settings
     *
     * @return array<mixed[]>
     */
    protected function getEnvironmentConstantsFromCallbacksMapping(): array
    {
        return [];
    }

    /**
     * Provide the configuration for all components required in the plugin
     *
     * @return array<class-string<ModuleInterface>,array<string,mixed>> [key]: Module class, [value]: Configuration
     */
    public function getModuleClassConfiguration(): array
    {
        // Retrieve this service from the SystemContainer
        $systemInstanceManager = SystemInstanceManagerFacade::getInstance();
        /** @var EndpointHelpers */
        $endpointHelpers = $systemInstanceManager->getInstance(EndpointHelpers::class);

        /**
         * The admin endpoints (other than the one for Persisted Queries)
         * can have predefined configuration. Persisted Queries must take
         * the configuration from the corresponding Schema Configuration.
         */
        $predefinedAdminEndpointModuleClassConfiguration = [];
        if ($endpointHelpers->isRequestingNonPersistedQueryAdminGraphQLEndpoint()) {
            $endpointGroup = $endpointHelpers->getAdminGraphQLEndpointGroup();
            $predefinedAdminEndpointModuleClassConfiguration = $this->getPredefinedAdminEndpointModuleClassConfiguration($endpointGroup);
        }

        /** @var array<class-string<ModuleInterface>,array<string,mixed>> */
        return array_merge_recursive(
            $this->getPredefinedModuleClassConfiguration(),
            $predefinedAdminEndpointModuleClassConfiguration,
            $this->getBasedOnModuleEnabledStateModuleClassConfiguration(),
        );
    }

    /**
     * Get the fixed configuration for all components required in the plugin
     * when requesting some specific group in the admin endpoint.
     *
     * Allow developers to inject their own endpointGroups and corresponding
     * configuration via a filter hook
     *
     * @return array<class-string<ModuleInterface>,array<string,mixed>> [key]: Module class, [value]: Configuration
     */
    private function getPredefinedAdminEndpointModuleClassConfiguration(string $endpointGroup): array
    {
        return apply_filters(
            HookNames::ADMIN_ENDPOINT_GROUP_MODULE_CONFIGURATION,
            $this->doGetPredefinedAdminEndpointModuleClassConfiguration($endpointGroup),
            $endpointGroup
        );
    }

    /**
     * Get the fixed configuration for all components required in the plugin
     * when requesting some specific group in the admin endpoint
     *
     * @return array<class-string<ModuleInterface>,array<string,mixed>> [key]: Module class, [value]: Configuration
     */
    protected function doGetPredefinedAdminEndpointModuleClassConfiguration(string $endpointGroup): array
    {
        return [];
    }

    /**
     * Get the fixed configuration for all components required in the plugin
     *
     * @return array<class-string<ModuleInterface>,array<string,mixed>> [key]: Module class, [value]: Configuration
     */
    protected function getPredefinedModuleClassConfiguration(): array
    {
        return [];
    }

    /**
     * Add configuration values if modules are enabled or disabled
     *
     * @return array<class-string<ModuleInterface>,array<string,mixed>> $moduleClassConfiguration [key]: Module class, [value]: Configuration
     */
    protected function getBasedOnModuleEnabledStateModuleClassConfiguration(): array
    {
        $moduleRegistry = SystemModuleRegistryFacade::getInstance();
        $moduleClassConfiguration = [];

        $moduleToModuleClassConfigurationMappings = $this->getModuleToModuleClassConfigurationMapping();
        foreach ($moduleToModuleClassConfigurationMappings as $mapping) {
            // Copy the state (enabled/disabled) to the module configuration
            /** @var string */
            $module = $mapping['module'];
            $value = $moduleRegistry->isModuleEnabled($module);
            // Make explicit it can be null so that PHPStan level 3 doesn't fail
            /** @var callable|null */
            $callback = $mapping['callback'] ?? null;
            if ($callback !== null) {
                $value = $callback($value);
            }
            /** @var class-string<ModuleInterface> */
            $class = $mapping['class'];
            /** @var string */
            $envVariable = $mapping['envVariable'];
            $moduleClassConfiguration[$class][$envVariable] = $value;
        }

        return $moduleClassConfiguration;
    }

    /**
     * @return array<mixed[]>
     */
    protected function getModuleToModuleClassConfigurationMapping(): array
    {
        return [];
    }

    /**
     * Add schema Module classes to skip initializing
     *
     * @return array<class-string<ModuleInterface>> List of `Module` class which must not initialize their Schema services
     */
    final public function getSchemaModuleClassesToSkip(): array
    {
        /**
         * If doing ?endpoint_group=pluginInternal,
         * always enable all schema-type modules
         */
        $systemInstanceManager = SystemInstanceManagerFacade::getInstance();
        /** @var EndpointHelpers */
        $endpointHelpers = $systemInstanceManager->getInstance(EndpointHelpers::class);
        if (
            $this->alwaysEnableAllSchemaTypeModulesForAdminPluginInternalGraphQLEndpoint()
            && $endpointHelpers->isRequestingAdminPluginInternalGraphQLEndpoint()
        ) {
            return [];
        }

        /**
         * Check `is_admin` as loading the GraphiQL client is not executing
         * the endpoint, yet it will also generate the service container,
         * which will be cached and shared from then on.
         */
        $isAdmin = is_admin();
        $isRequestingAdminPersistedQueryGraphQLEndpoint = $endpointHelpers->isRequestingAdminPersistedQueryGraphQLEndpoint();
        if ($isAdmin
            && !$isRequestingAdminPersistedQueryGraphQLEndpoint
        ) {
            /**
             * Private endpoints: Check Settings to decide if to
             * disable the "Schema modules" or not
             */
            $userSettingsManager = UserSettingsManagerFacade::getInstance();
            if (
                !$userSettingsManager->getSetting(
                    PluginGeneralSettingsFunctionalityModuleResolver::PRIVATE_ENDPOINTS,
                    PluginGeneralSettingsFunctionalityModuleResolver::OPTION_DISABLE_SCHEMA_MODULES_IN_PRIVATE_ENDPOINTS
                )
            ) {
                return [];
            }
        }

        $schemaModuleClassesToSkip = $this->doGetSchemaModuleClassesToSkip();

        /**
         * Public endpoints: cannot be customized
         */
        if (
            /**
             * Check `is_admin` (instead of `isRequestingAdminGraphQLEndpoint`)
             * as loading the GraphiQL client is not executing the endpoint
             * yet it will also generate the service container, which will
             * be cached and shared from then on.
             */
            !$isAdmin
            || $isRequestingAdminPersistedQueryGraphQLEndpoint
        ) {
            return $schemaModuleClassesToSkip;
        }

        /**
         * Private endpoints: Allow to not disable modules on custom
         * admin endpoints, for some specific group.
         */
        return apply_filters(
            HookNames::ADMIN_ENDPOINT_GROUP_MODULE_CLASSES_TO_SKIP,
            $schemaModuleClassesToSkip,
            $endpointHelpers->getAdminGraphQLEndpointGroup()
        );
    }

    /**
     * @return array<class-string<ModuleInterface>> List of `Module` class which must not initialize their Schema services
     */
    private function doGetSchemaModuleClassesToSkip(): array
    {
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
     * Indicate if, when doing ?endpoint_group=pluginInternal,
     * all the schema-type modules must still be enabled (even
     * if they've been disabled).
     *
     * @todo Review is this right?
     */
    protected function alwaysEnableAllSchemaTypeModulesForAdminPluginInternalGraphQLEndpoint(): bool
    {
        return false;
    }

    /**
     * Provide the list of modules to check if they are enabled and,
     * if they are not, what module classes must skip initialization
     *
     * @return array<string,array<class-string<ModuleInterface>>>
     */
    protected function getModuleClassesToSkipIfModuleDisabled(): array
    {
        return [];
    }
}
