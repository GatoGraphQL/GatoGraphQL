<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPageAttachers;

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
use GraphQLByPoP\GraphQLClientsForWP\ComponentConfiguration as GraphQLClientsForWPComponentConfiguration;
use Symfony\Contracts\Service\Attribute\Required;

class BottomMenuPageAttacher extends AbstractPluginMenuPageAttacher
{
    protected MenuPageHelper $menuPageHelper;
    protected ModuleRegistryInterface $moduleRegistry;
    protected UserAuthorizationInterface $userAuthorization;
    protected SettingsMenuPage $settingsMenuPage;
    protected ModuleDocumentationMenuPage $moduleDocumentationMenuPage;
    protected ModulesMenuPage $modulesMenuPage;
    protected ReleaseNotesAboutMenuPage $releaseNotesAboutMenuPage;
    protected AboutMenuPage $aboutMenuPage;

    #[Required]
    public function autowireBottomMenuPageAttacher(
        MenuPageHelper $menuPageHelper,
        ModuleRegistryInterface $moduleRegistry,
        UserAuthorizationInterface $userAuthorization,
        SettingsMenuPage $settingsMenuPage,
        ModuleDocumentationMenuPage $moduleDocumentationMenuPage,
        ModulesMenuPage $modulesMenuPage,
        ReleaseNotesAboutMenuPage $releaseNotesAboutMenuPage,
        AboutMenuPage $aboutMenuPage,
    ): void {
        $this->menuPageHelper = $menuPageHelper;
        $this->moduleRegistry = $moduleRegistry;
        $this->userAuthorization = $userAuthorization;
        $this->settingsMenuPage = $settingsMenuPage;
        $this->moduleDocumentationMenuPage = $moduleDocumentationMenuPage;
        $this->modulesMenuPage = $modulesMenuPage;
        $this->releaseNotesAboutMenuPage = $releaseNotesAboutMenuPage;
        $this->aboutMenuPage = $aboutMenuPage;
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
                $this->settingsMenuPage->getScreenID(),
                [$this->settingsMenuPage, 'print']
            )
        ) {
            $this->settingsMenuPage->setHookName($hookName);
        }

        if ($this->moduleRegistry->isModuleEnabled(ClientFunctionalityModuleResolver::GRAPHIQL_FOR_SINGLE_ENDPOINT)) {
            global $submenu;
            $clientPath = GraphQLClientsForWPComponentConfiguration::getGraphiQLClientEndpoint();
            $submenu[$this->getMenuName()][] = [
                __('GraphiQL (public client)', 'graphql-api'),
                'read',
                home_url($clientPath),
            ];
        }

        if ($this->moduleRegistry->isModuleEnabled(ClientFunctionalityModuleResolver::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT)) {
            global $submenu;
            $clientPath = GraphQLClientsForWPComponentConfiguration::getVoyagerClientEndpoint();
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
        $aboutMenuPage = $this->getAboutMenuPage();
        if (isset($_GET['page']) && $_GET['page'] == $aboutMenuPage->getScreenID()) {
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
            $this->menuPageHelper->isDocumentationScreen() ?
                $this->moduleDocumentationMenuPage
                : $this->modulesMenuPage;
    }

    /**
     * Either the About menu page, or the Release Notes menu page,
     * based on parameter ?tab="docs" or not
     */
    protected function getAboutMenuPage(): MenuPageInterface
    {
        return
            $this->menuPageHelper->isDocumentationScreen() ?
                $this->releaseNotesAboutMenuPage
                : $this->aboutMenuPage;
    }
}
