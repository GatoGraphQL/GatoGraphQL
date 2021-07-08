<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;

use GraphQLAPI\GraphQLAPI\ComponentConfiguration;
use GraphQLAPI\GraphQLAPI\Config\PluginConfigurationHelpers;
use GraphQLAPI\GraphQLAPI\Environment;
use GraphQLAPI\GraphQLAPI\Facades\Registries\SystemModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\CacheFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ClientFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\OperationalFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\PerformanceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\PluginManagementFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\UserInterfaceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\PluginManagement\MainPluginManager;
use GraphQLAPI\GraphQLAPI\PluginManagement\PluginConfigurationHelper;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\AbstractMainPluginConfiguration;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\SettingsMenuPage;
use GraphQLByPoP\GraphQLClientsForWP\ComponentConfiguration as GraphQLClientsForWPComponentConfiguration;
use GraphQLByPoP\GraphQLClientsForWP\Environment as GraphQLClientsForWPEnvironment;
use GraphQLByPoP\GraphQLEndpointForWP\ComponentConfiguration as GraphQLEndpointForWPComponentConfiguration;
use GraphQLByPoP\GraphQLEndpointForWP\Environment as GraphQLEndpointForWPEnvironment;
use GraphQLByPoP\GraphQLQuery\Environment as GraphQLQueryEnvironment;
use GraphQLByPoP\GraphQLServer\ComponentConfiguration as GraphQLServerComponentConfiguration;
use GraphQLByPoP\GraphQLServer\Configuration\MutationSchemes;
use GraphQLByPoP\GraphQLServer\Environment as GraphQLServerEnvironment;
use PoP\AccessControl\ComponentConfiguration as AccessControlComponentConfiguration;
use PoP\AccessControl\Environment as AccessControlEnvironment;
use PoP\AccessControl\Schema\SchemaModes;
use PoP\APIEndpoints\EndpointUtils;
use PoP\CacheControl\ComponentConfiguration as CacheControlComponentConfiguration;
use PoP\CacheControl\Environment as CacheControlEnvironment;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationHelpers;
use PoP\ComponentModel\Environment as ComponentModelEnvironment;
use PoP\ComponentModel\Facades\Instances\SystemInstanceManagerFacade;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Engine\ComponentConfiguration as EngineComponentConfiguration;
use PoP\Engine\Environment as EngineEnvironment;
use PoP\Root\Environment as RootEnvironment;
use PoPSchema\Categories\ComponentConfiguration as CategoriesComponentConfiguration;
use PoPSchema\Categories\Environment as CategoriesEnvironment;
use PoPSchema\CommentMeta\ComponentConfiguration as CommentMetaComponentConfiguration;
use PoPSchema\CommentMeta\Environment as CommentMetaEnvironment;
use PoPSchema\CustomPostMeta\ComponentConfiguration as CustomPostMetaComponentConfiguration;
use PoPSchema\CustomPostMeta\Environment as CustomPostMetaEnvironment;
use PoPSchema\CustomPosts\ComponentConfiguration as CustomPostsComponentConfiguration;
use PoPSchema\CustomPosts\Environment as CustomPostsEnvironment;
use PoPSchema\GenericCustomPosts\ComponentConfiguration as GenericCustomPostsComponentConfiguration;
use PoPSchema\GenericCustomPosts\Environment as GenericCustomPostsEnvironment;
use PoPSchema\Pages\ComponentConfiguration as PagesComponentConfiguration;
use PoPSchema\Pages\Environment as PagesEnvironment;
use PoPSchema\Posts\ComponentConfiguration as PostsComponentConfiguration;
use PoPSchema\Posts\Environment as PostsEnvironment;
use PoPSchema\SchemaCommons\Constants\Behaviors;
use PoPSchema\Settings\ComponentConfiguration as SettingsComponentConfiguration;
use PoPSchema\Settings\Environment as SettingsEnvironment;
use PoPSchema\Tags\ComponentConfiguration as TagsComponentConfiguration;
use PoPSchema\Tags\Environment as TagsEnvironment;
use PoPSchema\TaxonomyMeta\ComponentConfiguration as TaxonomyMetaComponentConfiguration;
use PoPSchema\TaxonomyMeta\Environment as TaxonomyMetaEnvironment;
use PoPSchema\UserMeta\ComponentConfiguration as UserMetaComponentConfiguration;
use PoPSchema\UserMeta\Environment as UserMetaEnvironment;
use PoPSchema\Users\ComponentConfiguration as UsersComponentConfiguration;
use PoPSchema\Users\Environment as UsersEnvironment;


/**
 * Sets the configuration in all the PoP components.
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
 * For that, on the WordPress admin, go to Settings => Permalinks and click on Save changes
 */
abstract class AbstractPluginConfiguration
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
            $hookName = ComponentConfigurationHelpers::getHookName(
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
                    if (PluginConfigurationHelpers::isWPConfigConstantDefined($envVariable)) {
                        return PluginConfigurationHelpers::getWPConfigConstantValue($envVariable);
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
            if (getenv($envVariable) !== false || PluginConfigurationHelpers::isWPConfigConstantDefined($envVariable)) {
                continue;
            }
            $hookName = ComponentConfigurationHelpers::getHookName(
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
                    if (!is_null($callback)) {
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
            if (getenv($envVariable) !== false || PluginConfigurationHelpers::isWPConfigConstantDefined($envVariable)) {
                continue;
            }
            $hookName = ComponentConfigurationHelpers::getHookName(
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
}
