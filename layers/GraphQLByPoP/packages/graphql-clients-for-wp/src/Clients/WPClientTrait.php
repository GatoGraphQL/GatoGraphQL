<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLClientsForWP\Clients;

use GraphQLByPoP\GraphQLClientsForWP\Module;
use GraphQLByPoP\GraphQLClientsForWP\ModuleConfiguration;
use GraphQLByPoP\GraphQLEndpointForWP\Module as GraphQLEndpointForWPModule;
use GraphQLByPoP\GraphQLEndpointForWP\ModuleConfiguration as GraphQLEndpointForWPModuleConfiguration;
use PoP\Root\App;

trait WPClientTrait
{
    /**
     * Base URL
     */
    protected function getModuleBaseURL(): ?string
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getGraphQLClientsComponentURL();
    }
    /**
     * Base Dir
     */
    protected function getModuleBaseDir(): string
    {
        return dirname(__FILE__, 3);
    }

    /**
     * Endpoint URL or URL Path
     */
    protected function getEndpointURLOrURLPath(): string
    {
        /** @var GraphQLEndpointForWPModuleConfiguration */
        $moduleConfiguration = App::getModule(GraphQLEndpointForWPModule::class)->getConfiguration();
        return $moduleConfiguration->getGraphQLAPIEndpoint();
    }
}
