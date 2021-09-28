<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPageAttachers;

use Symfony\Contracts\Service\Attribute\Required;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use GraphQLAPI\GraphQLAPI\Services\Helpers\MenuPageHelper;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\GraphiQLMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\GraphQLVoyagerMenuPage;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

class TopMenuPageAttacher extends AbstractPluginMenuPageAttacher
{
    protected MenuPageHelper $menuPageHelper;
    protected ModuleRegistryInterface $moduleRegistry;
    protected UserAuthorizationInterface $userAuthorization;
    protected GraphiQLMenuPage $graphiQLMenuPage;
    protected GraphQLVoyagerMenuPage $graphQLVoyagerMenuPage;

    #[Required]
    public function autowireTopMenuPageAttacher(
        MenuPageHelper $menuPageHelper,
        ModuleRegistryInterface $moduleRegistry,
        UserAuthorizationInterface $userAuthorization,
        GraphiQLMenuPage $graphiQLMenuPage,
        GraphQLVoyagerMenuPage $graphQLVoyagerMenuPage,
    ) {
        $this->menuPageHelper = $menuPageHelper;
        $this->moduleRegistry = $moduleRegistry;
        $this->userAuthorization = $userAuthorization;
        $this->graphiQLMenuPage = $graphiQLMenuPage;
        $this->graphQLVoyagerMenuPage = $graphQLVoyagerMenuPage;
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
        $schemaEditorAccessCapability = $this->userAuthorization->getSchemaEditorAccessCapability();

        if (
            $hookName = \add_submenu_page(
                $this->getMenuName(),
                __('GraphiQL', 'graphql-api'),
                __('GraphiQL', 'graphql-api'),
                $schemaEditorAccessCapability,
                $this->getMenuName(),
                [$this->graphiQLMenuPage, 'print']
            )
        ) {
            $this->graphiQLMenuPage->setHookName($hookName);
        }

        if (
            $hookName = \add_submenu_page(
                $this->getMenuName(),
                __('Interactive Schema', 'graphql-api'),
                __('Interactive Schema', 'graphql-api'),
                $schemaEditorAccessCapability,
                $this->graphQLVoyagerMenuPage->getScreenID(),
                [$this->graphQLVoyagerMenuPage, 'print']
            )
        ) {
            $this->graphQLVoyagerMenuPage->setHookName($hookName);
        }
    }
}
