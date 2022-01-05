<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLClientsForWP\Clients;

use PoP\Root\Managers\ComponentManager;
use GraphQLByPoP\GraphQLClientsForWP\Component;
use GraphQLByPoP\GraphQLClientsForWP\ComponentConfiguration;

class VoyagerClient extends AbstractClient
{
    /**
     * Indicate if the client is disabled
     */
    protected function isClientDisabled(): bool
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = \PoP\Engine\App::getComponentManager()->getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->isVoyagerClientEndpointDisabled();
    }
    protected function getEndpoint(): string
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = \PoP\Engine\App::getComponentManager()->getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getVoyagerClientEndpoint();
    }
    protected function getClientRelativePath(): string
    {
        return '/clients/voyager';
    }
    protected function getJSFilename(): string
    {
        return 'voyager.js';
    }
}
