<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPageAttachers;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\ClientFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use GatoGraphQL\GatoGraphQL\Security\UserAuthorizationInterface;
use GatoGraphQL\GatoGraphQL\Services\Helpers\MenuPageHelper;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\GraphQLVoyagerMenuPage;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\GraphiQLMenuPage;

class TopMenuPageAttacher extends AbstractPluginMenuPageAttacher
{
    private ?MenuPageHelper $menuPageHelper = null;
    private ?ModuleRegistryInterface $moduleRegistry = null;
    private ?UserAuthorizationInterface $userAuthorization = null;
    private ?GraphiQLMenuPage $graphiQLMenuPage = null;
    private ?GraphQLVoyagerMenuPage $graphQLVoyagerMenuPage = null;

    final public function setMenuPageHelper(MenuPageHelper $menuPageHelper): void
    {
        $this->menuPageHelper = $menuPageHelper;
    }
    final protected function getMenuPageHelper(): MenuPageHelper
    {
        if ($this->menuPageHelper === null) {
            /** @var MenuPageHelper */
            $menuPageHelper = $this->instanceManager->getInstance(MenuPageHelper::class);
            $this->menuPageHelper = $menuPageHelper;
        }
        return $this->menuPageHelper;
    }
    final public function setModuleRegistry(ModuleRegistryInterface $moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        if ($this->moduleRegistry === null) {
            /** @var ModuleRegistryInterface */
            $moduleRegistry = $this->instanceManager->getInstance(ModuleRegistryInterface::class);
            $this->moduleRegistry = $moduleRegistry;
        }
        return $this->moduleRegistry;
    }
    final public function setUserAuthorization(UserAuthorizationInterface $userAuthorization): void
    {
        $this->userAuthorization = $userAuthorization;
    }
    final protected function getUserAuthorization(): UserAuthorizationInterface
    {
        if ($this->userAuthorization === null) {
            /** @var UserAuthorizationInterface */
            $userAuthorization = $this->instanceManager->getInstance(UserAuthorizationInterface::class);
            $this->userAuthorization = $userAuthorization;
        }
        return $this->userAuthorization;
    }
    final public function setGraphiQLMenuPage(GraphiQLMenuPage $graphiQLMenuPage): void
    {
        $this->graphiQLMenuPage = $graphiQLMenuPage;
    }
    final protected function getGraphiQLMenuPage(): GraphiQLMenuPage
    {
        if ($this->graphiQLMenuPage === null) {
            /** @var GraphiQLMenuPage */
            $graphiQLMenuPage = $this->instanceManager->getInstance(GraphiQLMenuPage::class);
            $this->graphiQLMenuPage = $graphiQLMenuPage;
        }
        return $this->graphiQLMenuPage;
    }
    final public function setGraphQLVoyagerMenuPage(GraphQLVoyagerMenuPage $graphQLVoyagerMenuPage): void
    {
        $this->graphQLVoyagerMenuPage = $graphQLVoyagerMenuPage;
    }
    final protected function getGraphQLVoyagerMenuPage(): GraphQLVoyagerMenuPage
    {
        if ($this->graphQLVoyagerMenuPage === null) {
            /** @var GraphQLVoyagerMenuPage */
            $graphQLVoyagerMenuPage = $this->instanceManager->getInstance(GraphQLVoyagerMenuPage::class);
            $this->graphQLVoyagerMenuPage = $graphQLVoyagerMenuPage;
        }
        return $this->graphQLVoyagerMenuPage;
    }

    /**
     * Before adding the menus for the CPTs
     */
    protected function getPriority(): int
    {
        return 6;
    }

    public function addMenuPages(): void
    {
        $schemaEditorAccessCapability = $this->getUserAuthorization()->getSchemaEditorAccessCapability();

        $isSingleEndpointGraphiQLClientEnabled = $this->getModuleRegistry()->isModuleEnabled(ClientFunctionalityModuleResolver::GRAPHIQL_FOR_SINGLE_ENDPOINT);
        $isSingleEndpointVoyagerEnabled = $this->getModuleRegistry()->isModuleEnabled(ClientFunctionalityModuleResolver::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT);

        if (
            $hookName = \add_submenu_page(
                $this->getMenuName(),
                __('GraphiQL', 'gatographql'),
                $isSingleEndpointGraphiQLClientEnabled
                    ? __('🟡 GraphiQL (private)', 'gatographql')
                    : __('GraphiQL', 'gatographql'),
                $schemaEditorAccessCapability,
                $this->getMenuName(),
                [$this->getGraphiQLMenuPage(), 'print']
            )
        ) {
            $this->getGraphiQLMenuPage()->setHookName($hookName);
        }

        if (
            $hookName = \add_submenu_page(
                $this->getMenuName(),
                __('GraphQL Schema', 'gatographql'),
                $isSingleEndpointVoyagerEnabled
                    ? __('🟡 Schema (private)', 'gatographql')
                    : __('Schema', 'gatographql'),
                $schemaEditorAccessCapability,
                $this->getGraphQLVoyagerMenuPage()->getScreenID(),
                [$this->getGraphQLVoyagerMenuPage(), 'print']
            )
        ) {
            $this->getGraphQLVoyagerMenuPage()->setHookName($hookName);
        }
    }
}
