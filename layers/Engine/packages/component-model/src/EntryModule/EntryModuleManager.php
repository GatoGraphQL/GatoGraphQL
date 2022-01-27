<?php

declare(strict_types=1);

namespace PoP\ComponentModel\EntryModule;

use PoP\ComponentModel\EntryModule\EntryModuleManagerInterface;
use PoP\Root\Services\BasicServiceTrait;
use PoP\ModuleRouting\ModuleRoutingGroups;
use PoP\ModuleRouting\RouteModuleProcessorManagerInterface;

class EntryModuleManager implements EntryModuleManagerInterface
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

    public function getEntryModule(): ?array
    {
        return $this->getRouteModuleProcessorManager()->getRouteModuleByMostAllmatchingVarsProperties(ModuleRoutingGroups::ENTRYMODULE);
    }
}
