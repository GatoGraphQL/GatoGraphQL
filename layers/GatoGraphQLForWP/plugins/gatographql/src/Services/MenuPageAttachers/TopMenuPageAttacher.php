<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPageAttachers;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\ClientFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use GatoGraphQL\GatoGraphQL\Security\UserAuthorizationInterface;
use GatoGraphQL\GatoGraphQL\Services\Helpers\MenuPageHelper;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\GraphQLVoyagerMenuPage;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\GraphiQLMenuPage;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\SettingsMenuPage;

use function add_submenu_page;

class TopMenuPageAttacher extends AbstractPluginMenuPageAttacher
{
    use WithSettingsPageMenuPageAttacherTrait;

    private ?MenuPageHelper $menuPageHelper = null;
    private ?ModuleRegistryInterface $moduleRegistry = null;
    private ?UserAuthorizationInterface $userAuthorization = null;
    private ?GraphiQLMenuPage $graphiQLMenuPage = null;
    private ?GraphQLVoyagerMenuPage $graphQLVoyagerMenuPage = null;
    private ?SettingsMenuPage $settingsMenuPage = null;

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
    final protected function getGraphiQLMenuPage(): GraphiQLMenuPage
    {
        if ($this->graphiQLMenuPage === null) {
            /** @var GraphiQLMenuPage */
            $graphiQLMenuPage = $this->instanceManager->getInstance(GraphiQLMenuPage::class);
            $this->graphiQLMenuPage = $graphiQLMenuPage;
        }
        return $this->graphiQLMenuPage;
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
    final protected function getSettingsMenuPage(): SettingsMenuPage
    {
        if ($this->settingsMenuPage === null) {
            /** @var SettingsMenuPage */
            $settingsMenuPage = $this->instanceManager->getInstance(SettingsMenuPage::class);
            $this->settingsMenuPage = $settingsMenuPage;
        }
        return $this->settingsMenuPage;
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
        // If the private endpoint is not enabled, no need to add the clients
        $isPrivateEndpointDisabled = !$this->getModuleRegistry()->isModuleEnabled(EndpointFunctionalityModuleResolver::PRIVATE_ENDPOINT);
        if ($isPrivateEndpointDisabled) {
            $this->addSettingsMenuPage();
            return;
        }

        $schemaEditorAccessCapability = $this->getUserAuthorization()->getSchemaEditorAccessCapability();
        $isSingleEndpointGraphiQLClientEnabled = $this->getModuleRegistry()->isModuleEnabled(ClientFunctionalityModuleResolver::GRAPHIQL_FOR_SINGLE_ENDPOINT);
        $isSingleEndpointVoyagerEnabled = $this->getModuleRegistry()->isModuleEnabled(ClientFunctionalityModuleResolver::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT);
        $menuName = $this->getMenuName();

        if (
            $hookName = add_submenu_page(
                $menuName,
                __('GraphiQL', 'gatographql'),
                $isSingleEndpointGraphiQLClientEnabled
                    ? __('🟡 GraphiQL (private)', 'gatographql')
                    : __('GraphiQL', 'gatographql'),
                $schemaEditorAccessCapability,
                $menuName,
                [$this->getGraphiQLMenuPage(), 'print']
            )
        ) {
            $this->getGraphiQLMenuPage()->setHookName($hookName);
        }

        if (
            $hookName = add_submenu_page(
                $menuName,
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
