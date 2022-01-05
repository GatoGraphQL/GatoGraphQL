<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLClientsForWP\Clients;

use PoP\Root\Managers\ComponentManager;
use GraphQLByPoP\GraphQLClientsForWP\Component;
use GraphQLByPoP\GraphQLClientsForWP\ComponentConfiguration;

abstract class AbstractGraphiQLClient extends AbstractClient
{
    /**
     * Indicate if the client is disabled
     */
    protected function isClientDisabled(): bool
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = \PoP\Engine\App::getComponentManager()->getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->isGraphiQLClientEndpointDisabled();
    }
    protected function getEndpoint(): string
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = \PoP\Engine\App::getComponentManager()->getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getGraphiQLClientEndpoint();
    }
}
