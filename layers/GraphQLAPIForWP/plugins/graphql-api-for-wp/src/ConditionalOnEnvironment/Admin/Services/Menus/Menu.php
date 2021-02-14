<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\Menus;

use GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\Helpers\MenuPageHelper;
use GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\MenuPages\AboutMenuPage;
use GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\MenuPages\AbstractMenuPage;
use GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\MenuPages\GraphiQLMenuPage;
use GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\MenuPages\GraphQLVoyagerMenuPage;
use GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\MenuPages\ModuleDocumentationMenuPage;
use GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\MenuPages\ModulesMenuPage;
use GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\MenuPages\ReleaseNotesAboutMenuPage;
use GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\MenuPages\SettingsMenuPage;
use GraphQLAPI\GraphQLAPI\ConditionalOnEnvironment\Admin\Services\MenuPages\SupportMenuPage;
use GraphQLAPI\GraphQLAPI\Facades\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ClientFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorization;
use GraphQLByPoP\GraphQLClientsForWP\ComponentConfiguration as GraphQLClientsForWPComponentConfiguration;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;

/**
 * Admin menu class
 */
class Menu extends AbstractMenu
{
    public const NAME = 'graphql_api';

    protected MenuPageHelper $menuPageHelper;

    function __construct(MenuPageHelper $menuPageHelper)
    {
        $this->menuPageHelper = $menuPageHelper;
    }

    public static function getName(): string
    {
        return static::NAME;
    }

    public function addMenuPagesTop(): void
    {
        parent::addMenuPagesTop();

        $instanceManager = InstanceManagerFacade::getInstance();

        $schemaEditorAccessCapability = UserAuthorization::getSchemaEditorAccessCapability();
        \add_menu_page(
            __('GraphQL API', 'graphql-api'),
            __('GraphQL API', 'graphql-api'),
            $schemaEditorAccessCapability,
            self::NAME,
            function () {
            },
            'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxOC4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+DQo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkdyYXBoUUxfTG9nbyIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4Ig0KCSB5PSIwcHgiIHZpZXdCb3g9IjAgMCA0MDAgNDAwIiBlbmFibGUtYmFja2dyb3VuZD0ibmV3IDAgMCA0MDAgNDAwIiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxnPg0KCTxnPg0KCQk8Zz4NCgkJCQ0KCQkJCTxyZWN0IHg9IjEyMiIgeT0iLTAuNCIgdHJhbnNmb3JtPSJtYXRyaXgoLTAuODY2IC0wLjUgMC41IC0wLjg2NiAxNjMuMzE5NiAzNjMuMzEzNikiIGZpbGw9IiNFNTM1QUIiIHdpZHRoPSIxNi42IiBoZWlnaHQ9IjMyMC4zIi8+DQoJCTwvZz4NCgk8L2c+DQoJPGc+DQoJCTxnPg0KCQkJPHJlY3QgeD0iMzkuOCIgeT0iMjcyLjIiIGZpbGw9IiNFNTM1QUIiIHdpZHRoPSIzMjAuMyIgaGVpZ2h0PSIxNi42Ii8+DQoJCTwvZz4NCgk8L2c+DQoJPGc+DQoJCTxnPg0KCQkJDQoJCQkJPHJlY3QgeD0iMzcuOSIgeT0iMzEyLjIiIHRyYW5zZm9ybT0ibWF0cml4KC0wLjg2NiAtMC41IDAuNSAtMC44NjYgODMuMDY5MyA2NjMuMzQwOSkiIGZpbGw9IiNFNTM1QUIiIHdpZHRoPSIxODUiIGhlaWdodD0iMTYuNiIvPg0KCQk8L2c+DQoJPC9nPg0KCTxnPg0KCQk8Zz4NCgkJCQ0KCQkJCTxyZWN0IHg9IjE3Ny4xIiB5PSI3MS4xIiB0cmFuc2Zvcm09Im1hdHJpeCgtMC44NjYgLTAuNSAwLjUgLTAuODY2IDQ2My4zNDA5IDI4My4wNjkzKSIgZmlsbD0iI0U1MzVBQiIgd2lkdGg9IjE4NSIgaGVpZ2h0PSIxNi42Ii8+DQoJCTwvZz4NCgk8L2c+DQoJPGc+DQoJCTxnPg0KCQkJDQoJCQkJPHJlY3QgeD0iMTIyLjEiIHk9Ii0xMyIgdHJhbnNmb3JtPSJtYXRyaXgoLTAuNSAtMC44NjYgMC44NjYgLTAuNSAxMjYuNzkwMyAyMzIuMTIyMSkiIGZpbGw9IiNFNTM1QUIiIHdpZHRoPSIxNi42IiBoZWlnaHQ9IjE4NSIvPg0KCQk8L2c+DQoJPC9nPg0KCTxnPg0KCQk8Zz4NCgkJCQ0KCQkJCTxyZWN0IHg9IjEwOS42IiB5PSIxNTEuNiIgdHJhbnNmb3JtPSJtYXRyaXgoLTAuNSAtMC44NjYgMC44NjYgLTAuNSAyNjYuMDgyOCA0NzMuMzc2NikiIGZpbGw9IiNFNTM1QUIiIHdpZHRoPSIzMjAuMyIgaGVpZ2h0PSIxNi42Ii8+DQoJCTwvZz4NCgk8L2c+DQoJPGc+DQoJCTxnPg0KCQkJPHJlY3QgeD0iNTIuNSIgeT0iMTA3LjUiIGZpbGw9IiNFNTM1QUIiIHdpZHRoPSIxNi42IiBoZWlnaHQ9IjE4NSIvPg0KCQk8L2c+DQoJPC9nPg0KCTxnPg0KCQk8Zz4NCgkJCTxyZWN0IHg9IjMzMC45IiB5PSIxMDcuNSIgZmlsbD0iI0U1MzVBQiIgd2lkdGg9IjE2LjYiIGhlaWdodD0iMTg1Ii8+DQoJCTwvZz4NCgk8L2c+DQoJPGc+DQoJCTxnPg0KCQkJDQoJCQkJPHJlY3QgeD0iMjYyLjQiIHk9IjI0MC4xIiB0cmFuc2Zvcm09Im1hdHJpeCgtMC41IC0wLjg2NiAwLjg2NiAtMC41IDEyNi43OTUzIDcxNC4yODc1KSIgZmlsbD0iI0U1MzVBQiIgd2lkdGg9IjE0LjUiIGhlaWdodD0iMTYwLjkiLz4NCgkJPC9nPg0KCTwvZz4NCgk8cGF0aCBmaWxsPSIjRTUzNUFCIiBkPSJNMzY5LjUsMjk3LjljLTkuNiwxNi43LTMxLDIyLjQtNDcuNywxMi44Yy0xNi43LTkuNi0yMi40LTMxLTEyLjgtNDcuN2M5LjYtMTYuNywzMS0yMi40LDQ3LjctMTIuOA0KCQlDMzczLjUsMjU5LjksMzc5LjIsMjgxLjIsMzY5LjUsMjk3LjkiLz4NCgk8cGF0aCBmaWxsPSIjRTUzNUFCIiBkPSJNOTAuOSwxMzdjLTkuNiwxNi43LTMxLDIyLjQtNDcuNywxMi44Yy0xNi43LTkuNi0yMi40LTMxLTEyLjgtNDcuN2M5LjYtMTYuNywzMS0yMi40LDQ3LjctMTIuOA0KCQlDOTQuOCw5OSwxMDAuNSwxMjAuMyw5MC45LDEzNyIvPg0KCTxwYXRoIGZpbGw9IiNFNTM1QUIiIGQ9Ik0zMC41LDI5Ny45Yy05LjYtMTYuNy0zLjktMzgsMTIuOC00Ny43YzE2LjctOS42LDM4LTMuOSw0Ny43LDEyLjhjOS42LDE2LjcsMy45LDM4LTEyLjgsNDcuNw0KCQlDNjEuNCwzMjAuMyw0MC4xLDMxNC42LDMwLjUsMjk3LjkiLz4NCgk8cGF0aCBmaWxsPSIjRTUzNUFCIiBkPSJNMzA5LjEsMTM3Yy05LjYtMTYuNy0zLjktMzgsMTIuOC00Ny43YzE2LjctOS42LDM4LTMuOSw0Ny43LDEyLjhjOS42LDE2LjcsMy45LDM4LTEyLjgsNDcuNw0KCQlDMzQwLjEsMTU5LjQsMzE4LjcsMTUzLjcsMzA5LjEsMTM3Ii8+DQoJPHBhdGggZmlsbD0iI0U1MzVBQiIgZD0iTTIwMCwzOTUuOGMtMTkuMywwLTM0LjktMTUuNi0zNC45LTM0LjljMC0xOS4zLDE1LjYtMzQuOSwzNC45LTM0LjljMTkuMywwLDM0LjksMTUuNiwzNC45LDM0LjkNCgkJQzIzNC45LDM4MC4xLDIxOS4zLDM5NS44LDIwMCwzOTUuOCIvPg0KCTxwYXRoIGZpbGw9IiNFNTM1QUIiIGQ9Ik0yMDAsNzRjLTE5LjMsMC0zNC45LTE1LjYtMzQuOS0zNC45YzAtMTkuMywxNS42LTM0LjksMzQuOS0zNC45YzE5LjMsMCwzNC45LDE1LjYsMzQuOSwzNC45DQoJCUMyMzQuOSw1OC40LDIxOS4zLDc0LDIwMCw3NCIvPg0KPC9nPg0KPC9zdmc+DQo='
        );

        /**
         * @var GraphiQLMenuPage
         */
        $graphiQLMenuPage = $instanceManager->getInstance(GraphiQLMenuPage::class);
        if (
            $hookName = \add_submenu_page(
                self::NAME,
                __('GraphiQL', 'graphql-api'),
                __('GraphiQL', 'graphql-api'),
                $schemaEditorAccessCapability,
                self::NAME,
                [$graphiQLMenuPage, 'print']
            )
        ) {
            $graphiQLMenuPage->setHookName($hookName);
        }

        /**
         * @var GraphQLVoyagerMenuPage
         */
        $graphQLVoyagerMenuPage = $instanceManager->getInstance(GraphQLVoyagerMenuPage::class);
        if (
            $hookName = \add_submenu_page(
                self::NAME,
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
            ModuleDocumentationMenuPage::class :
            ModulesMenuPage::class;
    }

    /**
     * Either the About menu page, or the Release Notes menu page,
     * based on parameter ?tab="docs" or not
     */
    protected function getAboutMenuPageClass(): string
    {
        return
            $this->menuPageHelper->isDocumentationScreen() ?
            ReleaseNotesAboutMenuPage::class :
            AboutMenuPage::class;
    }

    public function addMenuPagesBottom(): void
    {
        parent::addMenuPagesBottom();

        $instanceManager = InstanceManagerFacade::getInstance();
        $menuPageClass = $this->getModuleMenuPageClass();
        /**
         * @var AbstractMenuPage
         */
        $modulesMenuPage = $instanceManager->getInstance($menuPageClass);
        /**
         * @var callable
         */
        $callable = [$modulesMenuPage, 'print'];
        if (
            $hookName = \add_submenu_page(
                self::NAME,
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
        $settingsMenuPage = $instanceManager->getInstance(SettingsMenuPage::class);
        if (
            $hookName = \add_submenu_page(
                self::NAME,
                __('Settings', 'graphql-api'),
                __('Settings', 'graphql-api'),
                'manage_options',
                $settingsMenuPage->getScreenID(),
                [$settingsMenuPage, 'print']
            )
        ) {
            $settingsMenuPage->setHookName($hookName);
        }

        $moduleRegistry = ModuleRegistryFacade::getInstance();
        if ($moduleRegistry->isModuleEnabled(ClientFunctionalityModuleResolver::GRAPHIQL_FOR_SINGLE_ENDPOINT)) {
            global $submenu;
            $clientPath = GraphQLClientsForWPComponentConfiguration::getGraphiQLClientEndpoint();
            $submenu[self::NAME][] = [
                __('GraphiQL (public client)', 'graphql-api'),
                'read',
                home_url($clientPath),
            ];
        }

        if ($moduleRegistry->isModuleEnabled(ClientFunctionalityModuleResolver::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT)) {
            global $submenu;
            $clientPath = GraphQLClientsForWPComponentConfiguration::getVoyagerClientEndpoint();
            $submenu[self::NAME][] = [
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
        $aboutMenuPage = $instanceManager->getInstance($aboutPageClass);
        if (isset($_GET['page']) && $_GET['page'] == $aboutMenuPage->getScreenID()) {
            if (
                $hookName = \add_submenu_page(
                    self::NAME,
                    __('About', 'graphql-api'),
                    __('About', 'graphql-api'),
                    'read',
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
        $supportMenuPage = $instanceManager->getInstance(SupportMenuPage::class);
        if (
            $hookName = \add_submenu_page(
                self::NAME,
                __('Support', 'graphql-api'),
                __('Support', 'graphql-api'),
                'read',
                $supportMenuPage->getScreenID(),
                [$supportMenuPage, 'print']
            )
        ) {
            $supportMenuPage->setHookName($hookName);
        }

        // $schemaEditorAccessCapability = UserAuthorization::getSchemaEditorAccessCapability();
        // if (\current_user_can($schemaEditorAccessCapability)) {
        //     global $submenu;
        //     $submenu[self::NAME][] = [
        //         __('Documentation', 'graphql-api'),
        //         $schemaEditorAccessCapability,
        //         'https://graphql-api.com/documentation/',
        //     ];
        // }
    }
}
