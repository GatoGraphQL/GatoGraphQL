<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\YAMLServicesTrait;
use GraphQLAPI\GraphQLAPI\Config\ServiceConfiguration;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\ComponentModel\Facades\Engine\DataloadingEngineFacade;
use PoP\ComponentModel\Environment as ComponentModelEnvironment;
use PoP\CacheControl\DirectiveResolvers\CacheControlDirectiveResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\CacheFunctionalityModuleResolver;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationHelpers;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\PerformanceFunctionalityModuleResolver;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use GraphQLAPI\GraphQLAPI\SchemaConfiguratorExecuters\EndpointSchemaConfiguratorExecuter;
use GraphQLAPI\GraphQLAPI\SchemaConfiguratorExecuters\PersistedQuerySchemaConfiguratorExecuter;
use GraphQLAPI\GraphQLAPI\SchemaConfiguratorExecuters\EditingPersistedQuerySchemaConfiguratorExecuter;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    use YAMLServicesTrait;

    // const VERSION = '0.1.0';

    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public static function getDependedComponentClasses(): array
    {
        return [
            \PoPSchema\GenericCustomPosts\Component::class,
            \PoPSchema\CommentMetaWP\Component::class,
            \GraphQLByPoP\GraphQLServer\Component::class,
            \PoPSchema\MediaWP\Component::class,
            \PoPSchema\PostsWP\Component::class,
            \PoPSchema\PagesWP\Component::class,
            \PoPSchema\CustomPostMediaWP\Component::class,
            \PoPSchema\CustomPostMetaWP\Component::class,
            \PoPSchema\TaxonomyQueryWP\Component::class,
            \PoPSchema\PostTagsWP\Component::class,
            \PoPSchema\UserRolesAccessControl\Component::class,
            \PoPSchema\UserRolesWP\Component::class,
            \PoPSchema\UserStateWP\Component::class,
            \PoPSchema\UserMetaWP\Component::class,
            \PoPSchema\CustomPostMutationsWP\Component::class,
            \PoPSchema\PostMutations\Component::class,
            \PoPSchema\CustomPostMediaMutationsWP\Component::class,
            \PoPSchema\CommentMutationsWP\Component::class,
            \PoPSchema\UserStateMutationsWP\Component::class,
            \PoPSchema\BasicDirectives\Component::class,
            \GraphQLByPoP\GraphQLClientsForWP\Component::class,
            \GraphQLByPoP\GraphQLEndpointForWP\Component::class,
            \GraphQLAPI\MarkdownConvertor\Component::class,
        ];
    }

    /**
     * Initialize services
     *
     * @param array<string, mixed> $configuration
     * @param string[] $skipSchemaComponentClasses
     */
    protected static function doInitialize(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        parent::doInitialize($configuration, $skipSchema, $skipSchemaComponentClasses);
        self::initYAMLServices(dirname(__DIR__));
        /**
         * FieldResolvers used to configure the services can also be accessed in the admin area
         */
        if (\is_admin()) {
            self::maybeInitYAMLSchemaServices(dirname(__DIR__), $skipSchema, '', 'admin-schema-services.yaml');
        }
        // Register the Cache services, if the module is not disabled
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        if ($moduleRegistry->isModuleEnabled(CacheFunctionalityModuleResolver::CONFIGURATION_CACHE)) {
            self::initYAMLServices(dirname(__DIR__), '', 'cache-services.yaml');
        }
        self::initComponentConfiguration();
        ServiceConfiguration::initialize();
    }

    protected static function initComponentConfiguration(): void
    {
        /**
         * Enable the schema entity registries, as to retrieve the type/directive resolver classes
         * from the type/directive names, saved in the DB in the ACL/CCL Custom Post Types
         */
        $hookName = ComponentConfigurationHelpers::getHookName(
            ComponentModelComponentConfiguration::class,
            ComponentModelEnvironment::ENABLE_SCHEMA_ENTITY_REGISTRIES
        );
        \add_filter(
            $hookName,
            fn () => true,
            PHP_INT_MAX,
            1
        );
    }

    /**
     * Boot component
     *
     * @return void
     */
    public static function beforeBoot(): void
    {
        parent::beforeBoot();

        // Initialize classes
        ContainerBuilderUtils::instantiateNamespaceServices(__NAMESPACE__ . '\\Hooks');
        ContainerBuilderUtils::attachFieldResolversFromNamespace(__NAMESPACE__ . '\\Admin\\FieldResolvers', false);
    }

    /**
     * Boot component
     *
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();

        // Enable the CacheControl, if the module is not disabled
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        if ($moduleRegistry->isModuleEnabled(PerformanceFunctionalityModuleResolver::CACHE_CONTROL)) {
            // Unless previewing the query
            if (!\is_preview()) {
                $dataloadingEngine = DataloadingEngineFacade::getInstance();
                $dataloadingEngine->addMandatoryDirectives([
                    CacheControlDirectiveResolver::getDirectiveName(),
                ]);
            }
        }

        // Configure the GraphQL query with Access/Cache Control Lists
        (new PersistedQuerySchemaConfiguratorExecuter())->init();
        (new EndpointSchemaConfiguratorExecuter())->init();
        (new EditingPersistedQuerySchemaConfiguratorExecuter())->init();
    }
}
