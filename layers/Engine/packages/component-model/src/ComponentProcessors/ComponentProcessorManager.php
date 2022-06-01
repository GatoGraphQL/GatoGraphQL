<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\Root\Services\BasicServiceTrait;

class ComponentProcessorManager implements ComponentProcessorManagerInterface
{
    use BasicServiceTrait;

    /**
     * @var array<string,array<string,ComponentProcessorInterface>>
     */
    private array $componentProcessors = [];

    /**
     * Return the ComponentProcessor that handles the Component
     */
    public function getProcessor(Component $component): ComponentProcessorInterface
    {
        if (!isset($this->componentProcessors[$component->processorClass][$component->name])) {
            $processorInstance = $this->getInstanceManager()->getInstance($component->processorClass);
            $this->componentProcessors[$component->processorClass][$component->name] = $processorInstance;
        }
        return $this->componentProcessors[$component->processorClass][$component->name];
    }
}
