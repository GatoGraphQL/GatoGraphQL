<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPageAttachers;

use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use GraphQLAPI\GraphQLAPI\Services\Helpers\MenuPageHelper;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\GraphiQLMenuPage;
use GraphQLAPI\GraphQLAPI\Services\MenuPages\GraphQLVoyagerMenuPage;
use PoP\ComponentModel\Instances\InstanceManagerInterface;

class TopMenuPageAttacher extends AbstractPluginMenuPageAttacher
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
}
