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

    private ?ComponentRoutingProcessorManagerInterface $routeComponentProcessorManager = null;

    final public function setComponentRoutingProcessorManager(ComponentRoutingProcessorManagerInterface $routeComponentProcessorManager): void
    {
        $this->routeComponentProcessorManager = $routeComponentProcessorManager;
    }
    final protected function getComponentRoutingProcessorManager(): ComponentRoutingProcessorManagerInterface
    {
        return $this->routeComponentProcessorManager ??= $this->instanceManager->getInstance(ComponentRoutingProcessorManagerInterface::class);
    }

    public function getEntryComponent(): ?array
    {
        return $this->getComponentRoutingProcessorManager()->getRoutingComponentByMostAllMatchingStateProperties(ComponentRoutingGroups::ENTRYCOMPONENT);
    }
}
