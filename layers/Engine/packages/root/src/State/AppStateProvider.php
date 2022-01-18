<?php

declare(strict_types=1);

namespace PoP\Root\State;

use PoP\Root\Routing\RoutingManagerInterface;
use PoP\Root\Routing\RequestNature;

class AppStateProvider extends AbstractAppStateProvider
{
    private ?RoutingManagerInterface $routingManager = null;

    final public function setRoutingManager(RoutingManagerInterface $routingManager): void
    {
        $this->routingManager = $routingManager;
    }
    final protected function getRoutingManager(): RoutingManagerInterface
    {
        return $this->routingManager ??= $this->instanceManager->getInstance(RoutingManagerInterface::class);
    }

    public function initialize(array &$state): void
    {
        $state['nature'] = $this->getRoutingManager()->getCurrentRequestNature();
        $state['route'] = $this->getRoutingManager()->getCurrentRoute();

        // Set the routing state under a unified entry
        $state['routing'] = [];
    }

    public function augment(array &$state): void
    {
        $nature = $state['nature'];
        $state['routing']['is-standard'] = $nature === RequestNature::GENERIC;
        $state['routing']['is-home'] = $nature === RequestNature::HOME;
        $state['routing']['is-404'] = $nature === RequestNature::NOTFOUND;
    }
}
