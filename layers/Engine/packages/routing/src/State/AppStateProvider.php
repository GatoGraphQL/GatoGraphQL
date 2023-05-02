<?php

declare(strict_types=1);

namespace PoP\Routing\State;

use PoP\Root\App;
use PoP\Root\Routing\RequestNature;
use PoP\Root\State\AbstractAppStateProvider;
use PoP\Routing\Module;
use PoP\Routing\ModuleConfiguration;
use PoP\Routing\RoutingManagerInterface;

class AppStateProvider extends AbstractAppStateProvider
{
    private ?RoutingManagerInterface $routingManager = null;

    final public function setRoutingManager(RoutingManagerInterface $routingManager): void
    {
        $this->routingManager = $routingManager;
    }
    final protected function getRoutingManager(): RoutingManagerInterface
    {
        /** @var RoutingManagerInterface */
        return $this->routingManager ??= $this->instanceManager->getInstance(RoutingManagerInterface::class);
    }

    /**
     * @param array<string,mixed> $state
     */
    public function initialize(array &$state): void
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();

        if (!$moduleConfiguration->enablePassingRoutingStateViaRequest()) {
            return;
        }
        $state['nature'] = $this->getRoutingManager()->getCurrentRequestNature();
        $state['route'] = $this->getRoutingManager()->getCurrentRoute();
    }

    /**
     * @param array<string,mixed> $state
     */
    public function augment(array &$state): void
    {
        $nature = $state['nature'];
        $state['routing']['is-home'] = $nature === RequestNature::HOME;
        $state['routing']['is-404'] = $nature === RequestNature::NOTFOUND;
    }
}
