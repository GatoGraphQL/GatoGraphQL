<?php

declare(strict_types=1);

namespace PoP\Engine\EntryModule;

use PoP\ComponentModel\EntryModule\EntryModuleManagerInterface;
use PoP\ModuleRouting\ModuleRoutingGroups;
use PoP\ModuleRouting\RouteModuleProcessorManagerInterface;
use Symfony\Contracts\Service\Attribute\Required;

class EntryModuleManager implements EntryModuleManagerInterface
{
    protected ?RouteModuleProcessorManagerInterface $routeModuleProcessorManager = null;

    public function setRouteModuleProcessorManager(RouteModuleProcessorManagerInterface $routeModuleProcessorManager): void
    {
        $this->routeModuleProcessorManager = $routeModuleProcessorManager;
    }
    protected function getRouteModuleProcessorManager(): RouteModuleProcessorManagerInterface
    {
        return $this->routeModuleProcessorManager ??= $this->getInstanceManager()->getInstance(RouteModuleProcessorManagerInterface::class);
    }

    //#[Required]
    final public function autowireEntryModuleManager(RouteModuleProcessorManagerInterface $routeModuleProcessorManager): void
    {
        $this->routeModuleProcessorManager = $routeModuleProcessorManager;
    }

    public function getEntryModule(): ?array
    {
        return $this->getRouteModuleProcessorManager()->getRouteModuleByMostAllmatchingVarsProperties(ModuleRoutingGroups::ENTRYMODULE);
    }
}
