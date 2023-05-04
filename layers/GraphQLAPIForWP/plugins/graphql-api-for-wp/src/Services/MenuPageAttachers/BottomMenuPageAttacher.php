<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPageAttachers;

use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use GraphQLAPI\GraphQLAPI\Services\Helpers\MenuPageHelper;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\AboutMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\MenuPageInterface;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\ModuleDocumentationMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\ModulesMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\RecipesMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\ReleaseNotesAboutMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\SettingsMenuPage;
use GraphQLAPI\GraphQLAPI\Services\Taxonomies\GraphQLEndpointCategoryTaxonomy;
use PoP\Root\App;

class BottomMenuPageAttacher extends AbstractPluginMenuPageAttacher
{
    private ?MenuPageHelper $menuPageHelper = null;
    private ?ModuleRegistryInterface $moduleRegistry = null;
    private ?UserAuthorizationInterface $userAuthorization = null;
    private ?SettingsMenuPage $settingsMenuPage = null;
    private ?ModuleDocumentationMenuPage $moduleDocumentationMenuPage = null;
    private ?ModulesMenuPage $modulesMenuPage = null;
    private ?ReleaseNotesAboutMenuPage $releaseNotesAboutMenuPage = null;
    private ?RecipesMenuPage $recipesMenuPage = null;
    private ?AboutMenuPage $aboutMenuPage = null;
    private ?GraphQLEndpointCategoryTaxonomy $graphQLEndpointCategoryTaxonomy = null;

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
    final public function setSettingsMenuPage(SettingsMenuPage $settingsMenuPage): void
    {
        $this->settingsMenuPage = $settingsMenuPage;
    }
    final protected function getSettingsMenuPage(): SettingsMenuPage
    {
        /** @var SettingsMenuPage */
        return $this->settingsMenuPage ??= $this->instanceManager->getInstance(SettingsMenuPage::class);
    }
    final public function setModuleDocumentationMenuPage(ModuleDocumentationMenuPage $moduleDocumentationMenuPage): void
    {
        $this->moduleDocumentationMenuPage = $moduleDocumentationMenuPage;
    }
    final protected function getModuleDocumentationMenuPage(): ModuleDocumentationMenuPage
    {
        /** @var ModuleDocumentationMenuPage */
        return $this->moduleDocumentationMenuPage ??= $this->instanceManager->getInstance(ModuleDocumentationMenuPage::class);
    }
    final public function setModulesMenuPage(ModulesMenuPage $modulesMenuPage): void
    {
        $this->modulesMenuPage = $modulesMenuPage;
    }
    final protected function getModulesMenuPage(): ModulesMenuPage
    {
        /** @var ModulesMenuPage */
        return $this->modulesMenuPage ??= $this->instanceManager->getInstance(ModulesMenuPage::class);
    }
    final public function setReleaseNotesAboutMenuPage(ReleaseNotesAboutMenuPage $releaseNotesAboutMenuPage): void
    {
        $this->releaseNotesAboutMenuPage = $releaseNotesAboutMenuPage;
    }
    final protected function getReleaseNotesAboutMenuPage(): ReleaseNotesAboutMenuPage
    {
        /** @var ReleaseNotesAboutMenuPage */
        return $this->releaseNotesAboutMenuPage ??= $this->instanceManager->getInstance(ReleaseNotesAboutMenuPage::class);
    }
    final public function setRecipesMenuPage(RecipesMenuPage $recipesMenuPage): void
    {
        $this->recipesMenuPage = $recipesMenuPage;
    }
    final protected function getRecipesMenuPage(): RecipesMenuPage
    {
        /** @var RecipesMenuPage */
        return $this->recipesMenuPage ??= $this->instanceManager->getInstance(RecipesMenuPage::class);
    }
    final public function setAboutMenuPage(AboutMenuPage $aboutMenuPage): void
    {
        $this->aboutMenuPage = $aboutMenuPage;
    }
    final protected function getAboutMenuPage(): AboutMenuPage
    {
        /** @var AboutMenuPage */
        return $this->aboutMenuPage ??= $this->instanceManager->getInstance(AboutMenuPage::class);
    }
    final public function setGraphQLEndpointCategoryTaxonomy(GraphQLEndpointCategoryTaxonomy $graphQLEndpointCategoryTaxonomy): void
    {
        $this->graphQLEndpointCategoryTaxonomy = $graphQLEndpointCategoryTaxonomy;
    }
    final protected function getGraphQLEndpointCategoryTaxonomy(): GraphQLEndpointCategoryTaxonomy
    {
        /** @var GraphQLEndpointCategoryTaxonomy */
        return $this->graphQLEndpointCategoryTaxonomy ??= $this->instanceManager->getInstance(GraphQLEndpointCategoryTaxonomy::class);
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
        $schemaEditorAccessCapability = $this->getUserAuthorization()->getSchemaEditorAccessCapability();
        $menuName = $this->getMenuName();

        /**
         * Add the "Endpoint Categories" link to the menu.
         * Adding `"show_in_menu" => true` or `"show_in_menu" => "graphql_api"`
         * doesn't work, so we must use a hack.
         *
         * @see https://stackoverflow.com/questions/48632394/wordpress-add-custom-taxonomy-to-custom-menu
         */
        $graphQLEndpointCategoriesLabel = $this->getGraphQLEndpointCategoryTaxonomy()->getTaxonomyPluralNames(true);
        $graphQLEndpointCategoriesCustomPostTypes = $this->getGraphQLEndpointCategoryTaxonomy()->getCustomPostTypes();
        $graphQLEndpointCategoriesRelativePath = sprintf(
            'edit-tags.php?taxonomy=%s&post_type=%s',
            $this->getGraphQLEndpointCategoryTaxonomy()->getTaxonomy(),
            /**
             * The custom taxonomy has 2 CPTs associated to it:
             *
             * - Custom Endpoints
             * - Persisted Queries
             *
             * The "count" column shows the number from both of them,
             * but clicking on it should take to neither. That's why
             * param "post_type" points to the non-existing "both of them" CPT,
             * and so the link in "count" is removed.
             */
            implode(
                ',',
                $graphQLEndpointCategoriesCustomPostTypes
            )
        );

        /**
         * When clicking on "Endpoint Categories" it would highlight
         * the Posts menu. With this code, it highlights the GraphQL API menu.
         *
         * @see https://stackoverflow.com/a/66094349
         */
        \add_filter(
            'parent_file',
            function (string $parent_file) use ($graphQLEndpointCategoriesRelativePath) {
                global $pagenow, $plugin_page, $submenu_file, $taxonomy;
                /**
                 * Check also we're not filtering Custom Endpoints or
                 * Persisted Queries by Category. In that case,
                 * keep the highlight on that menu item.
                 */
                if (
                    $pagenow !== 'edit.php'
                    && $taxonomy === $this->getGraphQLEndpointCategoryTaxonomy()->getTaxonomy()
                ) {
                    $plugin_page = $submenu_file = $graphQLEndpointCategoriesRelativePath;
                }
                return $parent_file;
            }
        );

        /**
         * Finally add the "Endpoint Categories" link to the menu.
         */
        \add_submenu_page(
            $menuName,
            $graphQLEndpointCategoriesLabel,
            $graphQLEndpointCategoriesLabel,
            $schemaEditorAccessCapability,
            $graphQLEndpointCategoriesRelativePath,
        );

        $modulesMenuPage = $this->getModuleMenuPage();
        /**
         * @var callable
         */
        $callable = [$modulesMenuPage, 'print'];
        if (
            $hookName = \add_submenu_page(
                $menuName,
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
                $menuName,
                __('Settings', 'graphql-api'),
                __('Settings', 'graphql-api'),
                'manage_options',
                $this->getSettingsMenuPage()->getScreenID(),
                [$this->getSettingsMenuPage(), 'print']
            )
        ) {
            $this->getSettingsMenuPage()->setHookName($hookName);
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
                    $menuName,
                    __('About', 'graphql-api'),
                    __('About', 'graphql-api'),
                    $schemaEditorAccessCapability,
                    $aboutMenuPage->getScreenID(),
                    [$aboutMenuPage, 'print']
                )
            ) {
                $aboutMenuPage->setHookName($hookName);
            }
        }

        $recipesMenuPage = $this->getRecipesMenuPage();
        /**
         * @var callable
         */
        $callable = [$recipesMenuPage, 'print'];
        if (
            $hookName = \add_submenu_page(
                $menuName,
                __('Recipes', 'graphql-api'),
                __('Recipes', 'graphql-api'),
                $schemaEditorAccessCapability,
                $recipesMenuPage->getScreenID(),
                $callable
            )
        ) {
            $recipesMenuPage->setHookName($hookName);
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
