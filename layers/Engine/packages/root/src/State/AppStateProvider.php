<?php

declare(strict_types=1);

namespace PoP\Root\State;

use PoP\Root\App;
use PoP\Root\Module;
use PoP\Root\ModuleConfiguration;
use PoP\Root\Routing\RequestNature;
use PoP\Root\Routing\RoutingManagerInterface;

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
        /** @var ModuleConfiguration */
        $componentConfiguration = App::getComponent(Module::class)->getConfiguration();

        if ($componentConfiguration->enablePassingRoutingStateViaRequest()) {
            $state['nature'] = $this->getRoutingManager()->getCurrentRequestNature();
            $state['route'] = $this->getRoutingManager()->getCurrentRoute();
        } else {
            $state['nature'] = RequestNature::GENERIC;
            $state['route'] = '';
        }
        $state['routing'] = [];
    }

    public function augment(array &$state): void
    {
        $nature = $state['nature'];
        $state['routing']['is-generic'] = $nature === RequestNature::GENERIC;
        $state['routing']['is-home'] = $nature === RequestNature::HOME;
        $state['routing']['is-404'] = $nature === RequestNature::NOTFOUND;
    }
}
