<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PluginSkeleton;



use GraphQLAPI\GraphQLAPI\ComponentConfiguration;
use GraphQLAPI\GraphQLAPI\Config\PluginConfigurationHelpers;
use GraphQLAPI\GraphQLAPI\Environment;
use GraphQLAPI\GraphQLAPI\Facades\CacheConfigurationManagerFacade;
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
use GraphQLAPI\GraphQLAPI\PluginEnvironment;
use GraphQLAPI\GraphQLAPI\PluginManagement\MainPluginManager;
use GraphQLAPI\GraphQLAPI\PluginManagement\PluginConfigurationHelper;
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
abstract class AbstractMainPluginConfiguration extends AbstractPluginConfiguration
{
    /**
     * Cache the Container Cache Configuration
     *
     * @var array<mixed> Array with args to pass to `AppLoader::initializeContainers` - [0]: cache container? (bool), [1]: container namespace (string|null)
     */
    protected static ?array $containerCacheConfigurationCache = null;

    /**
     * Provide the configuration to cache the container
     *
     * @return array<mixed> Array with args to pass to `AppLoader::initializeContainers`:
     *                      [0]: cache container? (bool)
     *                      [1]: container namespace (string|null)
     *                      [2]: container directory (string|null)
     */
    public static function getContainerCacheConfiguration(): array
    {
        if (is_null(self::$containerCacheConfigurationCache)) {
            $containerConfigurationCacheNamespace = null;
            $containerConfigurationCacheDirectory = null;
            $mainPluginCacheDir = (string) MainPluginManager::getConfig('cache-dir');
            if ($cacheContainerConfiguration = PluginEnvironment::isCachingEnabled()) {
                $cacheConfigurationManager = CacheConfigurationManagerFacade::getInstance();
                $containerConfigurationCacheNamespace = $cacheConfigurationManager->getNamespace();
                $containerConfigurationCacheDirectory = $mainPluginCacheDir . \DIRECTORY_SEPARATOR . 'service-containers';
            }
            self::$containerCacheConfigurationCache = [
                $cacheContainerConfiguration,
                $containerConfigurationCacheNamespace,
                $containerConfigurationCacheDirectory
            ];
        }
        return self::$containerCacheConfigurationCache;
    }
}
