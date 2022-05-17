<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPageAttachers;

use PoP\Root\App;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ClientFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use GraphQLAPI\GraphQLAPI\Services\Helpers\MenuPageHelper;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\AboutMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\MenuPageInterface;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\ModuleDocumentationMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\ModulesMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\ReleaseNotesAboutMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\SettingsMenuPage;
use GraphQLByPoP\GraphQLClientsForWP\Module as GraphQLClientsForWPModule;
use GraphQLByPoP\GraphQLClientsForWP\ModuleConfiguration as GraphQLClientsForWPModuleConfiguration;

class BottomMenuPageAttacher extends AbstractPluginMenuPageAttacher
{
    private ?MenuPageHelper $menuPageHelper = null;
    private ?ModuleRegistryInterface $moduleRegistry = null;
    private ?UserAuthorizationInterface $userAuthorization = null;
    private ?SettingsMenuPage $settingsMenuPage = null;
    private ?ModuleDocumentationMenuPage $moduleDocumentationMenuPage = null;
    private ?ModulesMenuPage $modulesMenuPage = null;
    private ?ReleaseNotesAboutMenuPage $releaseNotesAboutMenuPage = null;
    private ?AboutMenuPage $aboutMenuPage = null;

    final public function setMenuPageHelper(MenuPageHelper $menuPageHelper): void
    {
        $this->menuPageHelper = $menuPageHelper;
    }
    final protected function getMenuPageHelper(): MenuPageHelper
    {
        return $this->menuPageHelper ??= $this->instanceManager->getInstance(MenuPageHelper::class);
    }
    final public function setModuleRegistry(ModuleRegistryInterface $moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        return $this->moduleRegistry ??= $this->instanceManager->getInstance(ModuleRegistryInterface::class);
    }
    final public function setUserAuthorization(UserAuthorizationInterface $userAuthorization): void
    {
        $this->userAuthorization = $userAuthorization;
    }
    final protected function getUserAuthorization(): UserAuthorizationInterface
    {
        return $this->userAuthorization ??= $this->instanceManager->getInstance(UserAuthorizationInterface::class);
    }
    final public function setSettingsMenuPage(SettingsMenuPage $settingsMenuPage): void
    {
        $this->settingsMenuPage = $settingsMenuPage;
    }
    final protected function getSettingsMenuPage(): SettingsMenuPage
    {
        return $this->settingsMenuPage ??= $this->instanceManager->getInstance(SettingsMenuPage::class);
    }
    final public function setModuleDocumentationMenuPage(ModuleDocumentationMenuPage $moduleDocumentationMenuPage): void
    {
        $this->moduleDocumentationMenuPage = $moduleDocumentationMenuPage;
    }
    final protected function getModuleDocumentationMenuPage(): ModuleDocumentationMenuPage
    {
        return $this->moduleDocumentationMenuPage ??= $this->instanceManager->getInstance(ModuleDocumentationMenuPage::class);
    }
    final public function setModulesMenuPage(ModulesMenuPage $modulesMenuPage): void
    {
        $this->modulesMenuPage = $modulesMenuPage;
    }
    final protected function getModulesMenuPage(): ModulesMenuPage
    {
        return $this->modulesMenuPage ??= $this->instanceManager->getInstance(ModulesMenuPage::class);
    }
    final public function setReleaseNotesAboutMenuPage(ReleaseNotesAboutMenuPage $releaseNotesAboutMenuPage): void
    {
        $this->releaseNotesAboutMenuPage = $releaseNotesAboutMenuPage;
    }
    final protected function getReleaseNotesAboutMenuPage(): ReleaseNotesAboutMenuPage
    {
        return $this->releaseNotesAboutMenuPage ??= $this->instanceManager->getInstance(ReleaseNotesAboutMenuPage::class);
    }
    final public function setAboutMenuPage(AboutMenuPage $aboutMenuPage): void
    {
        $this->aboutMenuPage = $aboutMenuPage;
    }
    final protected function getAboutMenuPage(): AboutMenuPage
    {
        return $this->aboutMenuPage ??= $this->instanceManager->getInstance(AboutMenuPage::class);
    }

    /**
     * After adding the menus for the CPTs
     */
    protected function getPriority(): int
    {
        return 20;
    }

    public function addMenuPages(): void
    {
        $modulesMenuPage = $this->getModuleMenuPage();
        /**
         * @var callable
         */
        $callable = [$modulesMenuPage, 'print'];
        if (
            $hookName = \add_submenu_page(
                $this->getMenuName(),
                __('Modules', 'graphql-api'),
                __('Modules', 'graphql-api'),
                'manage_options',
                $modulesMenuPage->getScreenID(),
                $callable
            )
        ) {
            $modulesMenuPage->setHookName($hookName);
        }

        if (
            $hookName = \add_submenu_page(
                $this->getMenuName(),
                __('Settings', 'graphql-api'),
                __('Settings', 'graphql-api'),
                'manage_options',
                $this->getSettingsMenuPage()->getScreenID(),
                [$this->getSettingsMenuPage(), 'print']
            )
        ) {
            $this->getSettingsMenuPage()->setHookName($hookName);
        }

        /** @var GraphQLClientsForWPModuleConfiguration */
        $moduleConfiguration = App::getComponent(GraphQLClientsForWPModule::class)->getConfiguration();
        if ($this->getModuleRegistry()->isModuleEnabled(ClientFunctionalityModuleResolver::GRAPHIQL_FOR_SINGLE_ENDPOINT)) {
            global $submenu;
            $clientPath = $moduleConfiguration->getGraphiQLClientEndpoint();
            $submenu[$this->getMenuName()][] = [
                __('GraphiQL (public client)', 'graphql-api'),
                'read',
                home_url($clientPath),
            ];
        }

        if ($this->getModuleRegistry()->isModuleEnabled(ClientFunctionalityModuleResolver::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT)) {
            global $submenu;
            $clientPath = $moduleConfiguration->getVoyagerClientEndpoint();
            $submenu[$this->getMenuName()][] = [
                __('Interactive Schema (public client)', 'graphql-api'),
                'read',
                home_url($clientPath),
            ];
        }

        /**
         * Only show the About page when actually loading it
         * So it doesn't appear on the menu, but it's still available
         * to display the release notes on the modal window
         */
        $aboutMenuPage = $this->getReleaseNoteOrAboutMenuPage();
        if (App::query('page') === $aboutMenuPage->getScreenID()) {
            if (
                $hookName = \add_submenu_page(
                    $this->getMenuName(),
                    __('About', 'graphql-api'),
                    __('About', 'graphql-api'),
                    'manage_options',
                    $aboutMenuPage->getScreenID(),
                    [$aboutMenuPage, 'print']
                )
            ) {
                $aboutMenuPage->setHookName($hookName);
            }
        }
    }

    /**
     * Either the Modules menu page, or the Module Documentation menu page,
     * based on parameter ?tab="docs" or not
     */
    protected function getModuleMenuPage(): MenuPageInterface
    {
        return
            $this->getMenuPageHelper()->isDocumentationScreen() ?
                $this->getModuleDocumentationMenuPage()
                : $this->getModulesMenuPage();
    }

    /**
     * Either the About menu page, or the Release Notes menu page,
     * based on parameter ?tab="docs" or not
     */
    protected function getReleaseNoteOrAboutMenuPage(): MenuPageInterface
    {
        return
            $this->getMenuPageHelper()->isDocumentationScreen() ?
                $this->getReleaseNotesAboutMenuPage()
                : $this->getAboutMenuPage();
    }
}
