<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPageAttachers;

use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use GatoGraphQL\GatoGraphQL\Security\UserAuthorizationInterface;
use GatoGraphQL\GatoGraphQL\Services\Helpers\MenuPageHelper;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\AboutMenuPage;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\ExtensionDocModuleDocumentationMenuPage;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\ExtensionDocsMenuPage;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\ExtensionModuleDocumentationMenuPage;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\ExtensionsMenuPage;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\MenuPageInterface;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\ModuleDocumentationMenuPage;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\ModulesMenuPage;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\ReleaseNotesAboutMenuPage;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\SettingsMenuPage;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\TutorialMenuPage;
use GatoGraphQL\GatoGraphQL\Services\Taxonomies\GraphQLEndpointCategoryTaxonomy;
use PoP\Root\App;

use function add_submenu_page;

class BottomMenuPageAttacher extends AbstractPluginMenuPageAttacher
{
    use WithSettingsPageMenuPageAttacherTrait;

    private ?MenuPageHelper $menuPageHelper = null;
    private ?ModuleRegistryInterface $moduleRegistry = null;
    private ?UserAuthorizationInterface $userAuthorization = null;
    private ?SettingsMenuPage $settingsMenuPage = null;
    private ?ModuleDocumentationMenuPage $moduleDocumentationMenuPage = null;
    private ?ModulesMenuPage $modulesMenuPage = null;
    private ?ExtensionModuleDocumentationMenuPage $extensionModuleDocumentationMenuPage = null;
    private ?ExtensionDocModuleDocumentationMenuPage $extensionDocModuleDocumentationMenuPage = null;
    private ?ExtensionsMenuPage $extensionsMenuPage = null;
    private ?ReleaseNotesAboutMenuPage $releaseNotesAboutMenuPage = null;
    private ?ExtensionDocsMenuPage $extensionDocsMenuPage = null;
    private ?TutorialMenuPage $tutorialMenuPage = null;
    private ?AboutMenuPage $aboutMenuPage = null;
    private ?GraphQLEndpointCategoryTaxonomy $graphQLEndpointCategoryTaxonomy = null;

    final protected function getMenuPageHelper(): MenuPageHelper
    {
        if ($this->menuPageHelper === null) {
            /** @var MenuPageHelper */
            $menuPageHelper = $this->instanceManager->getInstance(MenuPageHelper::class);
            $this->menuPageHelper = $menuPageHelper;
        }
        return $this->menuPageHelper;
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
    final protected function getUserAuthorization(): UserAuthorizationInterface
    {
        if ($this->userAuthorization === null) {
            /** @var UserAuthorizationInterface */
            $userAuthorization = $this->instanceManager->getInstance(UserAuthorizationInterface::class);
            $this->userAuthorization = $userAuthorization;
        }
        return $this->userAuthorization;
    }
    final protected function getSettingsMenuPage(): SettingsMenuPage
    {
        if ($this->settingsMenuPage === null) {
            /** @var SettingsMenuPage */
            $settingsMenuPage = $this->instanceManager->getInstance(SettingsMenuPage::class);
            $this->settingsMenuPage = $settingsMenuPage;
        }
        return $this->settingsMenuPage;
    }
    final protected function getModuleDocumentationMenuPage(): ModuleDocumentationMenuPage
    {
        if ($this->moduleDocumentationMenuPage === null) {
            /** @var ModuleDocumentationMenuPage */
            $moduleDocumentationMenuPage = $this->instanceManager->getInstance(ModuleDocumentationMenuPage::class);
            $this->moduleDocumentationMenuPage = $moduleDocumentationMenuPage;
        }
        return $this->moduleDocumentationMenuPage;
    }
    final protected function getModulesMenuPage(): ModulesMenuPage
    {
        if ($this->modulesMenuPage === null) {
            /** @var ModulesMenuPage */
            $modulesMenuPage = $this->instanceManager->getInstance(ModulesMenuPage::class);
            $this->modulesMenuPage = $modulesMenuPage;
        }
        return $this->modulesMenuPage;
    }
    final protected function getExtensionModuleDocumentationMenuPage(): ExtensionModuleDocumentationMenuPage
    {
        if ($this->extensionModuleDocumentationMenuPage === null) {
            /** @var ExtensionModuleDocumentationMenuPage */
            $extensionModuleDocumentationMenuPage = $this->instanceManager->getInstance(ExtensionModuleDocumentationMenuPage::class);
            $this->extensionModuleDocumentationMenuPage = $extensionModuleDocumentationMenuPage;
        }
        return $this->extensionModuleDocumentationMenuPage;
    }
    final protected function getExtensionDocModuleDocumentationMenuPage(): ExtensionDocModuleDocumentationMenuPage
    {
        if ($this->extensionDocModuleDocumentationMenuPage === null) {
            /** @var ExtensionDocModuleDocumentationMenuPage */
            $extensionDocModuleDocumentationMenuPage = $this->instanceManager->getInstance(ExtensionDocModuleDocumentationMenuPage::class);
            $this->extensionDocModuleDocumentationMenuPage = $extensionDocModuleDocumentationMenuPage;
        }
        return $this->extensionDocModuleDocumentationMenuPage;
    }
    final protected function getExtensionsMenuPage(): ExtensionsMenuPage
    {
        if ($this->extensionsMenuPage === null) {
            /** @var ExtensionsMenuPage */
            $extensionsMenuPage = $this->instanceManager->getInstance(ExtensionsMenuPage::class);
            $this->extensionsMenuPage = $extensionsMenuPage;
        }
        return $this->extensionsMenuPage;
    }
    final protected function getReleaseNotesAboutMenuPage(): ReleaseNotesAboutMenuPage
    {
        if ($this->releaseNotesAboutMenuPage === null) {
            /** @var ReleaseNotesAboutMenuPage */
            $releaseNotesAboutMenuPage = $this->instanceManager->getInstance(ReleaseNotesAboutMenuPage::class);
            $this->releaseNotesAboutMenuPage = $releaseNotesAboutMenuPage;
        }
        return $this->releaseNotesAboutMenuPage;
    }
    final protected function getExtensionDocsMenuPage(): ExtensionDocsMenuPage
    {
        if ($this->extensionDocsMenuPage === null) {
            /** @var ExtensionDocsMenuPage */
            $extensionDocsMenuPage = $this->instanceManager->getInstance(ExtensionDocsMenuPage::class);
            $this->extensionDocsMenuPage = $extensionDocsMenuPage;
        }
        return $this->extensionDocsMenuPage;
    }
    final protected function getTutorialMenuPage(): TutorialMenuPage
    {
        if ($this->tutorialMenuPage === null) {
            /** @var TutorialMenuPage */
            $tutorialMenuPage = $this->instanceManager->getInstance(TutorialMenuPage::class);
            $this->tutorialMenuPage = $tutorialMenuPage;
        }
        return $this->tutorialMenuPage;
    }
    final protected function getAboutMenuPage(): AboutMenuPage
    {
        if ($this->aboutMenuPage === null) {
            /** @var AboutMenuPage */
            $aboutMenuPage = $this->instanceManager->getInstance(AboutMenuPage::class);
            $this->aboutMenuPage = $aboutMenuPage;
        }
        return $this->aboutMenuPage;
    }
    final protected function getGraphQLEndpointCategoryTaxonomy(): GraphQLEndpointCategoryTaxonomy
    {
        if ($this->graphQLEndpointCategoryTaxonomy === null) {
            /** @var GraphQLEndpointCategoryTaxonomy */
            $graphQLEndpointCategoryTaxonomy = $this->instanceManager->getInstance(GraphQLEndpointCategoryTaxonomy::class);
            $this->graphQLEndpointCategoryTaxonomy = $graphQLEndpointCategoryTaxonomy;
        }
        return $this->graphQLEndpointCategoryTaxonomy;
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
         * Adding `"show_in_menu" => true` or `"show_in_menu" => "gatographql"`
         * doesn't work, so we must use a hack.
         *
         * @see https://stackoverflow.com/questions/48632394/wordpress-add-custom-taxonomy-to-custom-menu
         */
        $graphQLEndpointCategoryTaxonomy = $this->getGraphQLEndpointCategoryTaxonomy();
        if (
            $graphQLEndpointCategoryTaxonomy->isServiceEnabled()
            && $graphQLEndpointCategoryTaxonomy->showInMenu() !== null
        ) {
            $graphQLEndpointCategoriesLabel = $graphQLEndpointCategoryTaxonomy->getTaxonomyPluralNames(true);
            $graphQLEndpointCategoriesCustomPostTypes = $graphQLEndpointCategoryTaxonomy->getCustomPostTypes();
            $graphQLEndpointCategoriesRelativePath = sprintf(
                'edit-tags.php?taxonomy=%s&post_type=%s',
                $graphQLEndpointCategoryTaxonomy->getTaxonomy(),
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
             * the Posts menu. With this code, it highlights the Gato GraphQL menu.
             *
             * @see https://stackoverflow.com/a/66094349
             */
            \add_filter(
                'parent_file',
                function (string $parent_file) use ($graphQLEndpointCategoriesRelativePath, $graphQLEndpointCategoryTaxonomy) {
                    global $pagenow, $plugin_page, $submenu_file, $taxonomy;
                    /**
                     * Check also we're not filtering Custom Endpoints or
                     * Persisted Queries by Category. In that case,
                     * keep the highlight on that menu item.
                     */
                    if (
                        $pagenow !== 'edit.php'
                        && $taxonomy === $graphQLEndpointCategoryTaxonomy->getTaxonomy()
                    ) {
                        $plugin_page = $submenu_file = $graphQLEndpointCategoriesRelativePath;
                    }
                    return $parent_file;
                }
            );

            /**
             * Finally add the "Endpoint Categories" link to the menu.
             */
            add_submenu_page(
                $menuName,
                $graphQLEndpointCategoriesLabel,
                $graphQLEndpointCategoriesLabel,
                $schemaEditorAccessCapability,
                $graphQLEndpointCategoriesRelativePath,
            );
        }

        $modulesMenuPage = $this->getModuleMenuPage();
        if ($modulesMenuPage->isServiceEnabled()) {
            $modulesMenuPageTitle = $modulesMenuPage->getMenuPageTitle();
            /**
             * @var callable
             */
            $callable = [$modulesMenuPage, 'print'];
            if (
                $hookName = add_submenu_page(
                    $menuName,
                    $modulesMenuPageTitle,
                    $modulesMenuPageTitle,
                    'manage_options',
                    $modulesMenuPage->getScreenID(),
                    $callable
                )
            ) {
                $modulesMenuPage->setHookName($hookName);
            }
        }

        $extensionsMenuPage = $this->getExtensionMenuPage();
        if ($extensionsMenuPage->isServiceEnabled()) {
            $extensionsMenuPageTitle = $extensionsMenuPage->getMenuPageTitle();
            /**
             * @var callable
             */
            $callable = [$extensionsMenuPage, 'print'];
            if (
                $hookName = add_submenu_page(
                    $menuName,
                    $extensionsMenuPageTitle,
                    $extensionsMenuPageTitle,
                    'manage_options',
                    $extensionsMenuPage->getScreenID(),
                    $callable
                )
            ) {
                $extensionsMenuPage->setHookName($hookName);
            }
        }

        /**
         * Only show the Extension Docs page when actually loading it
         * So it doesn't appear on the menu, but it's still available
         * when opening it via the Extensions page
         */
        $extensionDocsMenuPage = $this->getExtensionDocMenuPage();
        if (
            $extensionDocsMenuPage->isServiceEnabled()
            && App::query('page') === $extensionDocsMenuPage->getScreenID()
        ) {
            $extensionDocsMenuPageTitle = $extensionDocsMenuPage->getMenuPageTitle();
            /**
             * @var callable
             */
            $callable = [$extensionDocsMenuPage, 'print'];
            if (
                $hookName = add_submenu_page(
                    $menuName,
                    $extensionDocsMenuPageTitle,
                    $extensionDocsMenuPageTitle,
                    'manage_options',
                    $extensionDocsMenuPage->getScreenID(),
                    $callable
                )
            ) {
                $extensionDocsMenuPage->setHookName($hookName);
            }
        }

        // If the private endpoint is disabled, the Settings page becomes the default one
        $isPrivateEndpointDisabled = !$this->getModuleRegistry()->isModuleEnabled(EndpointFunctionalityModuleResolver::PRIVATE_ENDPOINT);
        if (!$isPrivateEndpointDisabled) {
            $this->addSettingsMenuPage();
        }

        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($moduleConfiguration->enableSchemaTutorialPage()) {
            $tutorialMenuPage = $this->getTutorialMenuPage();
            $tutorialMenuPageTitle = $tutorialMenuPage->getMenuPageTitle();
            /**
             * @var callable
             */
            $callable = [$tutorialMenuPage, 'print'];
            if (
                $hookName = add_submenu_page(
                    $menuName,
                    $tutorialMenuPageTitle,
                    $tutorialMenuPageTitle,
                    $schemaEditorAccessCapability,
                    $tutorialMenuPage->getScreenID(),
                    $callable
                )
            ) {
                $tutorialMenuPage->setHookName($hookName);
            }
        }

        $aboutMenuPage = $this->getReleaseNoteOrAboutMenuPage();
        if ($aboutMenuPage->isServiceEnabled()) {
            $aboutMenuPageTitle = $aboutMenuPage->getMenuPageTitle();
            if (
                $hookName = add_submenu_page(
                    $menuName,
                    $aboutMenuPageTitle,
                    $aboutMenuPageTitle,
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
     * Either the Extensions menu page, or the Extension Documentation menu page,
     * based on parameter ?tab="docs" or not
     */
    protected function getExtensionMenuPage(): MenuPageInterface
    {
        $extensionsMenuPage = $this->getExtensionsMenuPage();
        $isExtensionModuleDocumentationMenuPage = $extensionsMenuPage->getScreenID() === App::query('page')
            && $this->getMenuPageHelper()->isDocumentationScreen();
        return $isExtensionModuleDocumentationMenuPage
            ? $this->getExtensionModuleDocumentationMenuPage()
            : $extensionsMenuPage;
    }

    /**
     * Either the Extensions menu page, or the Extension Documentation menu page,
     * based on parameter ?tab="docs" or not
     */
    protected function getExtensionDocMenuPage(): MenuPageInterface
    {
        $extensionDocsMenuPage = $this->getExtensionDocsMenuPage();
        $isExtensionDocModuleDocumentationMenuPage = $extensionDocsMenuPage->getScreenID() === App::query('page')
            && $this->getMenuPageHelper()->isDocumentationScreen();
        return $isExtensionDocModuleDocumentationMenuPage
            ? $this->getExtensionDocModuleDocumentationMenuPage()
            : $extensionDocsMenuPage;
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
