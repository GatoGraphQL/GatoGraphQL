<?php

declare(strict_types=1);

namespace PoP\Engine\EntryModule;

use PoP\ComponentModel\EntryModule\EntryModuleManagerInterface;
use PoP\ModuleRouting\ModuleRoutingGroups;
use PoP\ModuleRouting\RouteModuleProcessorManagerInterface;
use Symfony\Contracts\Service\Attribute\Required;

class EntryModuleManager implements EntryModuleManagerInterface
{
    protected RouteModuleProcessorManagerInterface $routeModuleProcessorManager;

    #[Required]
    public function autowireEntryModuleManager(RouteModuleProcessorManagerInterface $routeModuleProcessorManager): void
    {
        $this->routeModuleProcessorManager = $routeModuleProcessorManager;
    }

    public function getEntryModule(): ?array
    {
        return $this->routeModuleProcessorManager->getRouteModuleByMostAllmatchingVarsProperties(ModuleRoutingGroups::ENTRYMODULE);
    }
}
