<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use GraphQLAPI\GraphQLAPI\Container\CompilerPasses\RegisterUserAuthorizationSchemeCompilerPass;
use GraphQLAPI\GraphQLAPI\Container\HybridCompilerPasses\RegisterModuleResolverCompilerPass;
use GraphQLAPI\GraphQLAPI\Facades\Registries\SystemModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\Facades\UserSettingsManagerFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ClientFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\PerformanceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\AbstractPluginComponent;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use PoP\ComponentModel\Facades\Instances\SystemInstanceManagerFacade;

/**
 * Initialize component
 */
class Component extends AbstractPluginComponent
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
            \GraphQLByPoP\GraphQLServer\Component::class,
            \PoPSchema\MediaWP\Component::class,
            \PoPSchema\PostsWP\Component::class,
            \PoPSchema\PagesWP\Component::class,
            \PoPSchema\CustomPostMediaWP\Component::class,
            \PoPSchema\TaxonomyQueryWP\Component::class,
            \PoPSchema\PostTagsWP\Component::class,
            \PoPSchema\PostCategoriesWP\Component::class,
            \PoPSchema\UserRolesAccessControl\Component::class,
            \PoPSchema\UserRolesWP\Component::class,
            \PoPSchema\UserStateWP\Component::class,
            \PoPSchema\CustomPostMutationsWP\Component::class,
            \PoPSchema\PostMutations\Component::class,
            \PoPSchema\CustomPostMediaMutationsWP\Component::class,
            \PoPSchema\PostTagMutationsWP\Component::class,
            \PoPSchema\PostCategoryMutationsWP\Component::class,
            \PoPSchema\CommentMutationsWP\Component::class,
            \PoPSchema\UserStateMutationsWP\Component::class,
            \PoPSchema\MenusWP\Component::class,
            \PoPSchema\SettingsWP\Component::class,
            \PoPSchema\CommentMetaWP\Component::class,
            \PoPSchema\CustomPostMetaWP\Component::class,
            \PoPSchema\TaxonomyMetaWP\Component::class,
            \PoPSchema\UserMetaWP\Component::class,
            \GraphQLByPoP\GraphQLClientsForWP\Component::class,
            \GraphQLByPoP\GraphQLEndpointForWP\Component::class,
            \GraphQLAPI\MarkdownConvertor\Component::class,
            \GraphQLAPI\ExternalDependencyWrappers\Component::class,
        ];
    }

    /**
     * Compiler Passes for the System Container
     *
     * @return string[]
     */
    public static function getSystemContainerCompilerPassClasses(): array
    {
        return [
            RegisterModuleResolverCompilerPass::class,
            RegisterUserAuthorizationSchemeCompilerPass::class,
        ];
    }

    /**
     * Initialize services for the system container.
     */
    protected static function initializeSystemContainerServices(): void
    {
        parent::initializeSystemContainerServices();

        if (\is_admin()) {
            self::initSystemServices(dirname(__DIR__), '/ConditionalOnContext/Admin');
        }
    }

    /**
     * Initialize services
     *
     * @param array<string, mixed> $configuration
     * @param string[] $skipSchemaComponentClasses
     */
    protected static function initializeContainerServices(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        parent::initializeContainerServices(
            $configuration,
            $skipSchema,
            $skipSchemaComponentClasses
        );
        // Override DI services
        self::initServices(dirname(__DIR__), '/Overrides');
        // Conditional DI settings
        /**
         * FieldResolvers used to configure the services can also be accessed in the admin area
         */
        if (\is_admin()) {
            self::initServices(dirname(__DIR__), '/ConditionalOnContext/Admin');

            // The WordPress editor can access the full GraphQL schema,
            // including "unrestricted" admin fields, so cache it individually.
            // Retrieve this service from the SystemContainer
            $systemInstanceManager = SystemInstanceManagerFacade::getInstance();
            /** @var EndpointHelpers */
            $endpointHelpers = $systemInstanceManager->getInstance(EndpointHelpers::class);
            self::initSchemaServices(
                dirname(__DIR__),
                !$endpointHelpers->isRequestingAdminFixedSchemaGraphQLEndpoint(),
                '/ConditionalOnContext/Admin/ConditionalOnContext/Editor'
            );
        }
        $moduleRegistry = SystemModuleRegistryFacade::getInstance();
        if ($moduleRegistry->isModuleEnabled(PerformanceFunctionalityModuleResolver::CACHE_CONTROL)) {
            self::initServices(dirname(__DIR__), '/ConditionalOnContext/CacheControl/Overrides');
        }
        // Maybe use GraphiQL with Explorer
        $userSettingsManager = UserSettingsManagerFacade::getInstance();
        $isGraphiQLExplorerEnabled = $moduleRegistry->isModuleEnabled(ClientFunctionalityModuleResolver::GRAPHIQL_EXPLORER);
        if (
            \is_admin()
            && $isGraphiQLExplorerEnabled
            && $userSettingsManager->getSetting(
                ClientFunctionalityModuleResolver::GRAPHIQL_EXPLORER,
                ClientFunctionalityModuleResolver::OPTION_USE_IN_ADMIN_CLIENT
            )
        ) {
            self::initServices(dirname(__DIR__), '/ConditionalOnContext/Admin/ConditionalOnContext/GraphiQLExplorerInAdminClient/Overrides');
        }
        if ($isGraphiQLExplorerEnabled) {
            if (
                $userSettingsManager->getSetting(
                    ClientFunctionalityModuleResolver::GRAPHIQL_EXPLORER,
                    ClientFunctionalityModuleResolver::OPTION_USE_IN_ADMIN_PERSISTED_QUERIES
                )
            ) {
                self::initServices(dirname(__DIR__), '/ConditionalOnContext/GraphiQLExplorerInAdminPersistedQueries/Overrides');
            }
            if (
                $userSettingsManager->getSetting(
                    ClientFunctionalityModuleResolver::GRAPHIQL_EXPLORER,
                    ClientFunctionalityModuleResolver::OPTION_USE_IN_PUBLIC_CLIENT_FOR_SINGLE_ENDPOINT
                )
            ) {
                self::initServices(dirname(__DIR__), '/ConditionalOnContext/GraphiQLExplorerInSingleEndpointPublicClient/Overrides');
            }
            if (
                $userSettingsManager->getSetting(
                    ClientFunctionalityModuleResolver::GRAPHIQL_EXPLORER,
                    ClientFunctionalityModuleResolver::OPTION_USE_IN_PUBLIC_CLIENT_FOR_CUSTOM_ENDPOINTS
                )
            ) {
                self::initServices(dirname(__DIR__), '/ConditionalOnContext/GraphiQLExplorerInCustomEndpointPublicClient/Overrides');
            }
        }
    }
}
