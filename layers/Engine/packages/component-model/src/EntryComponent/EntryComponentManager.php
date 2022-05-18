<?php

declare(strict_types=1);

namespace PoP\ComponentModel\EntryComponent;

use PoP\ComponentModel\EntryComponent\EntryComponentManagerInterface;
use PoP\Root\Services\BasicServiceTrait;
use PoP\ComponentRouting\ComponentRoutingGroups;
use PoP\ComponentRouting\RouteModuleProcessorManagerInterface;

class EntryComponentManager implements EntryComponentManagerInterface
{
    use BasicServiceTrait;

    private ?RouteModuleProcessorManagerInterface $routeModuleProcessorManager = null;

    final public function setRouteModuleProcessorManager(RouteModuleProcessorManagerInterface $routeModuleProcessorManager): void
    {
        $this->routeModuleProcessorManager = $routeModuleProcessorManager;
    }
    final protected function getRouteModuleProcessorManager(): RouteModuleProcessorManagerInterface
    {
        return $this->routeModuleProcessorManager ??= $this->instanceManager->getInstance(RouteModuleProcessorManagerInterface::class);
    }

    public function getEntryComponent(): ?array
    {
        return $this->getRouteModuleProcessorManager()->getRouteModuleByMostAllmatchingVarsProperties(ComponentRoutingGroups::ENTRYCOMPONENT);
    }
}
