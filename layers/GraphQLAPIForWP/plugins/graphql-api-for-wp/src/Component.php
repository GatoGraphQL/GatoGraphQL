<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use GraphQLAPI\GraphQLAPI\Config\ServiceConfiguration;
use GraphQLAPI\GraphQLAPI\Container\CompilerPasses\RegisterAccessControlRuleBlockCompilerPass;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\CacheFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ClientFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\PerformanceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\SchemaConfiguratorExecuters\EditingPersistedQuerySchemaConfiguratorExecuter;
use GraphQLAPI\GraphQLAPI\SchemaConfiguratorExecuters\EndpointSchemaConfiguratorExecuter;
use GraphQLAPI\GraphQLAPI\SchemaConfiguratorExecuters\PersistedQuerySchemaConfiguratorExecuter;
use PoP\CacheControl\DirectiveResolvers\CacheControlDirectiveResolver;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;
use PoP\ComponentModel\ComponentConfiguration\ComponentConfigurationHelpers;
use PoP\ComponentModel\Environment as ComponentModelEnvironment;
use PoP\ComponentModel\Facades\Engine\DataloadingEngineFacade;
use PoP\Root\Component\AbstractComponent;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
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
        self::initComponentConfiguration();
        self::initPHPServices(dirname(__DIR__));
        // Override DI services
        self::initPHPServices(dirname(__DIR__), '/Overrides');
        // Conditional DI settings
        /**
         * FieldResolvers used to configure the services can also be accessed in the admin area
         */
        if (\is_admin()) {
            self::initPHPServices(dirname(__DIR__), '/ConditionalOnEnvironment/Admin');
            self::initPHPServices(dirname(__DIR__), '/ConditionalOnEnvironment/Admin', 'schema-services.php');
        }
        // Register the Cache services, if the module is not disabled
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        if ($moduleRegistry->isModuleEnabled(CacheFunctionalityModuleResolver::CONFIGURATION_CACHE)) {
            self::initPHPServices(dirname(__DIR__), '/ConditionalOnEnvironment/ConfigurationCache/Overrides');
        }
        // Maybe use GraphiQL with Explorer
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        if (
            $moduleRegistry->isModuleEnabled(ClientFunctionalityModuleResolver::GRAPHIQL_EXPLORER)
            && $userSettingsManager->getSetting(
                ClientFunctionalityModuleResolver::GRAPHIQL_EXPLORER,
                ClientFunctionalityModuleResolver::OPTION_USE_IN_ADMIN_PERSISTED_QUERIES
            )
        ) {
            self::initPHPServices(dirname(__DIR__), '/ConditionalOnEnvironment/GraphiQLExplorerInAdminPersistedQueries/Overrides');
        }
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

    /**
     * Get all the compiler pass classes required to register on the container
     *
     * @return string[]
     */
    public static function getContainerCompilerPassClasses(): array
    {
        return [
            RegisterAccessControlRuleBlockCompilerPass::class,
        ];
    }
}
