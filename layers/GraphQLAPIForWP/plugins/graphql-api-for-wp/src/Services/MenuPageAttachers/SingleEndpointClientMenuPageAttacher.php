<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\MenuPageAttachers;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\ClientFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLByPoP\GraphQLClientsForWP\Module as GraphQLClientsForWPModule;
use GraphQLByPoP\GraphQLClientsForWP\ModuleConfiguration as GraphQLClientsForWPModuleConfiguration;
use PoP\Root\App;

class SingleEndpointClientMenuPageAttacher extends AbstractPluginMenuPageAttacher
{
    private ?ModuleRegistryInterface $moduleRegistry = null;

    final public function setModuleRegistry(ModuleRegistryInterface $moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        /** @var ModuleRegistryInterface */
        return $this->moduleRegistry ??= $this->instanceManager->getInstance(ModuleRegistryInterface::class);
    }

    /**
     * After adding the clients for the private endpoint
     */
    protected function getPriority(): int
    {
        return 7;
    }

    public function addMenuPages(): void
    {
        global $submenu;
        $menuName = $this->getMenuName();

        /** @var GraphQLClientsForWPModuleConfiguration */
        $moduleConfiguration = App::getModule(GraphQLClientsForWPModule::class)->getConfiguration();
        if ($this->getModuleRegistry()->isModuleEnabled(ClientFunctionalityModuleResolver::GRAPHIQL_FOR_SINGLE_ENDPOINT)) {
            $clientPath = $moduleConfiguration->getGraphiQLClientEndpoint();
            $submenu[$menuName][] = [
                __('🟢 GraphiQL (public client for single endpoint)', 'graphql-api'),
                'read',
                home_url($clientPath),
            ];
        }

        if ($this->getModuleRegistry()->isModuleEnabled(ClientFunctionalityModuleResolver::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT)) {
            $clientPath = $moduleConfiguration->getVoyagerClientEndpoint();
            $submenu[$menuName][] = [
                __('🟢 Interactive Schema (public client for single endpoint)', 'graphql-api'),
                'read',
                home_url($clientPath),
            ];
        }
    }
}
