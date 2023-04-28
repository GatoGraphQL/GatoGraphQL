<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI;

use GraphQLAPI\GraphQLAPI\Container\CompilerPasses\RegisterUserAuthorizationSchemeCompilerPass;
use GraphQLAPI\GraphQLAPI\Container\HybridCompilerPasses\RegisterModuleResolverCompilerPass;
use GraphQLAPI\GraphQLAPI\Container\HybridCompilerPasses\RegisterSettingsCategoryResolverCompilerPass;
use GraphQLAPI\GraphQLAPI\Facades\Registries\SystemModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\ModuleConfiguration;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\DeprecatedClientFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\PluginSkeleton\AbstractPluginModule;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use PoP\Root\Facades\Instances\SystemInstanceManagerFacade;
use PoP\Root\Module\ModuleInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class Module extends AbstractPluginModule
{
    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \GraphQLAPI\ExternalDependencyWrappers\Module::class,
            \GraphQLAPI\MarkdownConvertor\Module::class,
            \GraphQLAPI\PluginUtils\Module::class,
            \GraphQLByPoP\GraphQLClientsForWP\Module::class,
            \GraphQLByPoP\GraphQLEndpointForWP\Module::class,
            \GraphQLByPoP\GraphQLServer\Module::class,
            \PoPCMSSchema\CommentMutationsWP\Module::class,
            \PoPCMSSchema\CustomPostMediaMutationsWP\Module::class,
            \PoPCMSSchema\CustomPostMediaWP\Module::class,
            \PoPCMSSchema\CustomPostMutationsWP\Module::class,
            \PoPCMSSchema\PageMutations\Module::class,
            \PoPCMSSchema\PostCategoriesWP\Module::class,
            \PoPCMSSchema\PostCategoryMutationsWP\Module::class,
            \PoPCMSSchema\PageMediaMutations\Module::class,
            \PoPCMSSchema\PostMediaMutations\Module::class,
            \PoPCMSSchema\PostTagMutationsWP\Module::class,
            \PoPCMSSchema\PostTagsWP\Module::class,
            \PoPCMSSchema\SettingsWP\Module::class,
            \PoPCMSSchema\TaxonomyQueryWP\Module::class,
            \PoPCMSSchema\UserAvatarsWP\Module::class,
            \PoPCMSSchema\UserRolesWP\Module::class,
            \PoPCMSSchema\UserStateMutationsWP\Module::class,
            \PoPCMSSchema\UserStateWP\Module::class,
            \PoPWPSchema\CommentMeta\Module::class,
            \PoPWPSchema\Comments\Module::class,
            \PoPWPSchema\CustomPostMeta\Module::class,
            \PoPWPSchema\Media\Module::class,
            \PoPWPSchema\Menus\Module::class,
            \PoPWPSchema\Pages\Module::class,
            \PoPWPSchema\Posts\Module::class,
            \PoPWPSchema\TaxonomyMeta\Module::class,
            \PoPWPSchema\UserMeta\Module::class,
        ];
    }

    /**
     * Compiler Passes for the System Container
     *
     * @return array<class-string<CompilerPassInterface>>
     */
    public function getSystemContainerCompilerPassClasses(): array
    {
        return [
            RegisterModuleResolverCompilerPass::class,
            RegisterSettingsCategoryResolverCompilerPass::class,
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
     * @param array<class-string<ModuleInterface>> $skipSchemaModuleClasses
     */
    protected function initializeContainerServices(
        bool $skipSchema,
        array $skipSchemaModuleClasses,
    ): void {
        parent::initializeContainerServices(
            $skipSchema,
            $skipSchemaModuleClasses
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
                !$endpointHelpers->isRequestingAdminPluginOwnUseGraphQLEndpoint(),
                '/ConditionalOnContext/Admin/ConditionalOnContext/PluginOwnUse'
            );
        }
        $moduleRegistry = SystemModuleRegistryFacade::getInstance();
        $isGraphiQLExplorerEnabled = $moduleRegistry->isModuleEnabled(DeprecatedClientFunctionalityModuleResolver::GRAPHIQL_EXPLORER);
        if (
            \is_admin()
            && $isGraphiQLExplorerEnabled
        ) {
            $this->initServices(dirname(__DIR__), '/ConditionalOnContext/Admin/ConditionalOnContext/UseGraphiQLExplorer/Overrides');
        }
        if ($isGraphiQLExplorerEnabled) {
            $this->initServices(dirname(__DIR__), '/ConditionalOnContext/UseGraphiQLExplorer/Overrides');
        }
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(self::class)->getConfiguration();
        if ($moduleConfiguration->displayPROPluginInformationInMainPlugin()) {
            $this->initServices(dirname(__DIR__), '/ConditionalOnContext/PROPluginInformation');
            $this->initServices(dirname(__DIR__), '/ConditionalOnContext/PROPluginInformation/Overrides');
            $this->initServices(dirname(__DIR__), '/ConditionalOnContext/PROPluginInformation', 'module-services.yaml');
        }
    }
}
