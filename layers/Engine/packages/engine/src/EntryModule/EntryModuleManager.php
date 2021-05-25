<?php

declare(strict_types=1);

namespace PoP\Engine\EntryModule;

use PoP\ComponentModel\EntryModule\EntryModuleManagerInterface;
use PoP\ModuleRouting\ModuleRoutingGroups;
use PoP\ModuleRouting\RouteModuleProcessorManagerInterface;

class EntryModuleManager implements EntryModuleManagerInterface
{
    function __construct(
        protected RouteModuleProcessorManagerInterface $routeModuleProcessorManager,
    ) {
    }

    public function getEntryModule(): ?array
    {
        return $this->routeModuleProcessorManager->getRouteModuleByMostAllmatchingVarsProperties(ModuleRoutingGroups::ENTRYMODULE);
    }
}
