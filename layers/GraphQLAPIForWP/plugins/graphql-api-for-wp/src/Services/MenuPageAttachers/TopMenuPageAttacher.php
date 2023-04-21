<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPageAttachers;

use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use GraphQLAPI\GraphQLAPI\Services\Helpers\MenuPageHelper;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\GraphiQLMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\GraphQLVoyagerMenuPage;

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
        /** @var MenuPageHelper */
        return $this->menuPageHelper ??= $this->instanceManager->getInstance(MenuPageHelper::class);
    }
    final public function setModuleRegistry(ModuleRegistryInterface $moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        /** @var ModuleRegistryInterface */
        return $this->moduleRegistry ??= $this->instanceManager->getInstance(ModuleRegistryInterface::class);
    }
    final public function setUserAuthorization(UserAuthorizationInterface $userAuthorization): void
    {
        $this->userAuthorization = $userAuthorization;
    }
    final protected function getUserAuthorization(): UserAuthorizationInterface
    {
        /** @var UserAuthorizationInterface */
        return $this->userAuthorization ??= $this->instanceManager->getInstance(UserAuthorizationInterface::class);
    }
    final public function setGraphiQLMenuPage(GraphiQLMenuPage $graphiQLMenuPage): void
    {
        $this->graphiQLMenuPage = $graphiQLMenuPage;
    }
    final protected function getGraphiQLMenuPage(): GraphiQLMenuPage
    {
        /** @var GraphiQLMenuPage */
        return $this->graphiQLMenuPage ??= $this->instanceManager->getInstance(GraphiQLMenuPage::class);
    }
    final public function setGraphQLVoyagerMenuPage(GraphQLVoyagerMenuPage $graphQLVoyagerMenuPage): void
    {
        $this->graphQLVoyagerMenuPage = $graphQLVoyagerMenuPage;
    }
    final protected function getGraphQLVoyagerMenuPage(): GraphQLVoyagerMenuPage
    {
        /** @var GraphQLVoyagerMenuPage */
        return $this->graphQLVoyagerMenuPage ??= $this->instanceManager->getInstance(GraphQLVoyagerMenuPage::class);
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

        if (
            $hookName = \add_submenu_page(
                $this->getMenuName(),
                __('GraphiQL', 'graphql-api'),
                __('🟡 GraphiQL (private)', 'graphql-api'),
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
                __('GraphQL Schema', 'graphql-api'),
                __('🟡 Schema (private)', 'graphql-api'),
                $schemaEditorAccessCapability,
                $this->getGraphQLVoyagerMenuPage()->getScreenID(),
                [$this->getGraphQLVoyagerMenuPage(), 'print']
            )
        ) {
            $this->getGraphQLVoyagerMenuPage()->setHookName($hookName);
        }
    }
}
