<?php

declare(strict_types=1);

namespace PoP\ComponentModel\EntryComponent;

use PoP\ComponentModel\EntryComponent\EntryComponentManagerInterface;
use PoP\Root\Services\BasicServiceTrait;
use PoP\ComponentRouting\ComponentRoutingGroups;
use PoP\ComponentRouting\ComponentRoutingProcessorManagerInterface;

class EntryComponentManager implements EntryComponentManagerInterface
{
    use BasicServiceTrait;

    private ?ComponentRoutingProcessorManagerInterface $routeModuleProcessorManager = null;

    final public function setComponentRoutingProcessorManager(ComponentRoutingProcessorManagerInterface $routeModuleProcessorManager): void
    {
        $this->routeModuleProcessorManager = $routeModuleProcessorManager;
    }
    final protected function getComponentRoutingProcessorManager(): ComponentRoutingProcessorManagerInterface
    {
        return $this->routeModuleProcessorManager ??= $this->instanceManager->getInstance(ComponentRoutingProcessorManagerInterface::class);
    }

    public function getEntryComponent(): ?array
    {
        return $this->getComponentRoutingProcessorManager()->getRoutingComponentByMostAllMatchingStateProperties(ComponentRoutingGroups::ENTRYCOMPONENT);
    }
}
