<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLClientsForWP\Clients;

use PoP\Root\App;
use GraphQLByPoP\GraphQLClientsForWP\Module;
use GraphQLByPoP\GraphQLClientsForWP\ModuleConfiguration;

abstract class AbstractGraphiQLClient extends AbstractClient
{
    /**
     * Indicate if the client is disabled
     */
    protected function isClientDisabled(): bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getComponent(Module::class)->getConfiguration();
        return $moduleConfiguration->isGraphiQLClientEndpointDisabled();
    }
    public function getEndpoint(): string
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getComponent(Module::class)->getConfiguration();
        return $moduleConfiguration->getGraphiQLClientEndpoint();
    }
}
