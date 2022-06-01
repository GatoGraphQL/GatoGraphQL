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
        $componentProcessorClass = $component[0];
        $componentName = $component[1];
        if (!isset($this->componentProcessors[$componentProcessorClass][$componentName])) {
            $processorInstance = $this->getInstanceManager()->getInstance($componentProcessorClass);
            $this->componentProcessors[$componentProcessorClass][$componentName] = $processorInstance;
        }
        return $this->componentProcessors[$componentProcessorClass][$componentName];
    }
}
