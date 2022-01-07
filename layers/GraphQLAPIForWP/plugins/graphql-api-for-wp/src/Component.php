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
use PoP\Root\Facades\Instances\SystemInstanceManagerFacade;

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
    public function getDependedComponentClasses(): array
    {
        return [
            \PoPSchema\GenericCustomPosts\Component::class,
            \GraphQLByPoP\GraphQLServer\Component::class,
            \PoPSchema\CustomPostMediaWP\Component::class,
            \PoPSchema\TaxonomyQueryWP\Component::class,
            \PoPSchema\PostTagsWP\Component::class,
            \PoPSchema\PostCategoriesWP\Component::class,
            \PoPSchema\UserRolesAccessControl\Component::class,
            \PoPSchema\UserAvatarsWP\Component::class,
            \PoPSchema\UserRolesWP\Component::class,
            \PoPSchema\UserStateWP\Component::class,
            \PoPSchema\CustomPostMutationsWP\Component::class,
            \PoPSchema\PostMediaMutations\Component::class,
            \PoPSchema\CustomPostMediaMutationsWP\Component::class,
            \PoPSchema\PostTagMutationsWP\Component::class,
            \PoPSchema\PostCategoryMutationsWP\Component::class,
            \PoPSchema\CommentMutationsWP\Component::class,
            \PoPSchema\UserStateMutationsWP\Component::class,
            \PoPSchema\SettingsWP\Component::class,
            \PoPWPSchema\Comments\Component::class,
            \PoPWPSchema\Media\Component::class,
            \PoPWPSchema\Menus\Component::class,
            \PoPWPSchema\Pages\Component::class,
            \PoPWPSchema\Posts\Component::class,
            \PoPWPSchema\Users\Component::class,
            \PoPWPSchema\CommentMeta\Component::class,
            \PoPWPSchema\CustomPostMeta\Component::class,
            \PoPWPSchema\TaxonomyMeta\Component::class,
            \PoPWPSchema\UserMeta\Component::class,
            \GraphQLByPoP\GraphQLClientsForWP\Component::class,
            \GraphQLByPoP\GraphQLEndpointForWP\Component::class,
            \GraphQLAPI\ExternalDependencyWrappers\Component::class,
            \GraphQLAPI\MarkdownConvertor\Component::class,
            \GraphQLAPI\PluginUtils\Component::class,
        ];
    }

    /**
     * Compiler Passes for the System Container
     *
     * @return string[]
     */
    public function getSystemContainerCompilerPassClasses(): array
    {
        return [
            RegisterModuleResolverCompilerPass::class,
            RegisterUserAuthorizationSchemeCompilerPass::class,
        ];
    }

    /**
     * Initialize services for the system container.
     */
    protected function initializeSystemContainerServices(): void
    {
        parent::initializeSystemContainerServices();

        if (\is_admin()) {
            $this->initSystemServices(dirname(__DIR__), '/ConditionalOnContext/Admin');
        }
    }

    /**
     * Initialize services
     *
     * @param string[] $skipSchemaComponentClasses
     */
    protected function initializeContainerServices(
        bool $skipSchema,
        array $skipSchemaComponentClasses,
    ): void {
        parent::initializeContainerServices(
            $skipSchema,
            $skipSchemaComponentClasses
        );
        // Override DI services
        $this->initServices(dirname(__DIR__), '/Overrides');
        // Conditional DI settings
        /**
         * ObjectTypeFieldResolvers used to configure the services can also be accessed in the admin area
         */
        if (\is_admin()) {
            $this->initServices(dirname(__DIR__), '/ConditionalOnContext/Admin');

            // The WordPress editor can access the full GraphQL schema,
            // including "admin" fields, so cache it individually.
            // Retrieve this service from the SystemContainer
            $systemInstanceManager = SystemInstanceManagerFacade::getInstance();
            /** @var EndpointHelpers */
            $endpointHelpers = $systemInstanceManager->getInstance(EndpointHelpers::class);
            $this->initSchemaServices(
                dirname(__DIR__),
                !$endpointHelpers->isRequestingAdminFixedSchemaGraphQLEndpoint(),
                '/ConditionalOnContext/Admin/ConditionalOnContext/Editor'
            );
        }
        $moduleRegistry = SystemModuleRegistryFacade::getInstance();
        if ($moduleRegistry->isModuleEnabled(PerformanceFunctionalityModuleResolver::CACHE_CONTROL)) {
            $this->initServices(dirname(__DIR__), '/ConditionalOnContext/CacheControl/Overrides');
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
            $this->initServices(dirname(__DIR__), '/ConditionalOnContext/Admin/ConditionalOnContext/GraphiQLExplorerInAdminClient/Overrides');
        }
        if ($isGraphiQLExplorerEnabled) {
            if (
                $userSettingsManager->getSetting(
                    ClientFunctionalityModuleResolver::GRAPHIQL_EXPLORER,
                    ClientFunctionalityModuleResolver::OPTION_USE_IN_ADMIN_PERSISTED_QUERIES
                )
            ) {
                $this->initServices(dirname(__DIR__), '/ConditionalOnContext/GraphiQLExplorerInAdminPersistedQueries/Overrides');
            }
            if (
                $userSettingsManager->getSetting(
                    ClientFunctionalityModuleResolver::GRAPHIQL_EXPLORER,
                    ClientFunctionalityModuleResolver::OPTION_USE_IN_PUBLIC_CLIENT_FOR_SINGLE_ENDPOINT
                )
            ) {
                $this->initServices(dirname(__DIR__), '/ConditionalOnContext/GraphiQLExplorerInSingleEndpointPublicClient/Overrides');
            }
            if (
                $userSettingsManager->getSetting(
                    ClientFunctionalityModuleResolver::GRAPHIQL_EXPLORER,
                    ClientFunctionalityModuleResolver::OPTION_USE_IN_PUBLIC_CLIENT_FOR_CUSTOM_ENDPOINTS
                )
            ) {
                $this->initServices(dirname(__DIR__), '/ConditionalOnContext/GraphiQLExplorerInCustomEndpointPublicClient/Overrides');
            }
        }
    }
}
