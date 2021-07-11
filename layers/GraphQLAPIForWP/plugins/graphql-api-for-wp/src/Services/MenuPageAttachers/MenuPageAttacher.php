<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPageAttachers;

use GraphQLAPI\GraphQLAPI\Services\Helpers\MenuPageHelper;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\AboutMenuPage;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\ModulesMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\SupportMenuPage;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\AbstractMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\GraphiQLMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\SettingsMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\GraphQLVoyagerMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\ReleaseNotesAboutMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\ModuleDocumentationMenuPage;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ClientFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Menus\Menu;
use GraphQLByPoP\GraphQLClientsForWP\ComponentConfiguration as GraphQLClientsForWPComponentConfiguration;

class MenuPageAttacher extends AbstractMenuPageAttacher
{
    public function __construct(
        InstanceManagerInterface $instanceManager,
        protected MenuPageHelper $menuPageHelper,
        protected ModuleRegistryInterface $moduleRegistry,
        protected UserAuthorizationInterface $userAuthorization
    ) {
        parent::__construct(
            $instanceManager,
        );
    }

    public function getMenuClass(): string
    {
        return Menu::class;
    }

    public function addMenuPagesTop(): void
    {
        parent::addMenuPagesTop();

        $schemaEditorAccessCapability = $this->userAuthorization->getSchemaEditorAccessCapability();
        
        /**
         * @var GraphiQLMenuPage
         */
        $graphiQLMenuPage = $this->instanceManager->getInstance(GraphiQLMenuPage::class);
        if (
            $hookName = \add_submenu_page(
                $this->getMenuName(),
                __('GraphiQL', 'graphql-api'),
                __('GraphiQL', 'graphql-api'),
                $schemaEditorAccessCapability,
                $this->getMenuName(),
                [$graphiQLMenuPage, 'print']
            )
        ) {
            $graphiQLMenuPage->setHookName($hookName);
        }

        /**
         * @var GraphQLVoyagerMenuPage
         */
        $graphQLVoyagerMenuPage = $this->instanceManager->getInstance(GraphQLVoyagerMenuPage::class);
        if (
            $hookName = \add_submenu_page(
                $this->getMenuName(),
                __('Interactive Schema', 'graphql-api'),
                __('Interactive Schema', 'graphql-api'),
                $schemaEditorAccessCapability,
                $graphQLVoyagerMenuPage->getScreenID(),
                [$graphQLVoyagerMenuPage, 'print']
            )
        ) {
            $graphQLVoyagerMenuPage->setHookName($hookName);
        }
    }

    /**
     * Either the Modules menu page, or the Module Documentation menu page,
     * based on parameter ?tab="docs" or not
     */
    protected function getModuleMenuPageClass(): string
    {
        return
            $this->menuPageHelper->isDocumentationScreen() ?
                ModuleDocumentationMenuPage::class
                : ModulesMenuPage::class;
    }

    /**
     * Either the About menu page, or the Release Notes menu page,
     * based on parameter ?tab="docs" or not
     */
    protected function getAboutMenuPageClass(): string
    {
        return
            $this->menuPageHelper->isDocumentationScreen() ?
                ReleaseNotesAboutMenuPage::class
                : AboutMenuPage::class;
    }

    public function addMenuPagesBottom(): void
    {
        parent::addMenuPagesBottom();

        $menuPageClass = $this->getModuleMenuPageClass();
        /**
         * @var AbstractMenuPage
         */
        $modulesMenuPage = $this->instanceManager->getInstance($menuPageClass);
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

        /**
         * @var SettingsMenuPage
         */
        $settingsMenuPage = $this->instanceManager->getInstance(SettingsMenuPage::class);
        if (
            $hookName = \add_submenu_page(
                $this->getMenuName(),
                __('Settings', 'graphql-api'),
                __('Settings', 'graphql-api'),
                'manage_options',
                $settingsMenuPage->getScreenID(),
                [$settingsMenuPage, 'print']
            )
        ) {
            $settingsMenuPage->setHookName($hookName);
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
        $aboutPageClass = $this->getAboutMenuPageClass();
        /**
         * @var AbstractMenuPage
         */
        $aboutMenuPage = $this->instanceManager->getInstance($aboutPageClass);
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

        /**
         * @var SupportMenuPage
         */
        $supportMenuPage = $this->instanceManager->getInstance(SupportMenuPage::class);
        if (
            $hookName = \add_submenu_page(
                $this->getMenuName(),
                __('Support', 'graphql-api'),
                __('Support', 'graphql-api'),
                'manage_options',
                $supportMenuPage->getScreenID(),
                [$supportMenuPage, 'print']
            )
        ) {
            $supportMenuPage->setHookName($hookName);
        }

        // $schemaEditorAccessCapability = $this->userAuthorization->getSchemaEditorAccessCapability();
        // if (\current_user_can($schemaEditorAccessCapability)) {
        //     global $submenu;
        //     $submenu[$this->getMenuName()][] = [
        //         __('Documentation', 'graphql-api'),
        //         $schemaEditorAccessCapability,
        //         'https://graphql-api.com/documentation/',
        //     ];
        // }
    }
}
