<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPageAttachers;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\ClientFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use GraphQLByPoP\GraphQLClientsForWP\Module as GraphQLClientsForWPModule;
use GraphQLByPoP\GraphQLClientsForWP\ModuleConfiguration as GraphQLClientsForWPModuleConfiguration;
use PoP\Root\App;

class SingleEndpointClientMenuPageAttacher extends AbstractPluginMenuPageAttacher
{
    private ?ModuleRegistryInterface $moduleRegistry = null;

    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        if ($this->moduleRegistry === null) {
            /** @var ModuleRegistryInterface */
            $moduleRegistry = $this->instanceManager->getInstance(ModuleRegistryInterface::class);
            $this->moduleRegistry = $moduleRegistry;
        }
        return $this->moduleRegistry;
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
                __('🟢 GraphiQL (public)', 'gatographql'),
                'read',
                home_url($clientPath),
            ];
        }

        if ($this->getModuleRegistry()->isModuleEnabled(ClientFunctionalityModuleResolver::INTERACTIVE_SCHEMA_FOR_SINGLE_ENDPOINT)) {
            $clientPath = $moduleConfiguration->getVoyagerClientEndpoint();
            $submenu[$menuName][] = [
                __('🟢 Schema (public)', 'gatographql'),
                'read',
                home_url($clientPath),
            ];
        }
    }
}
