<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\Root\Services\BasicServiceTrait;

class ComponentProcessorManager implements ComponentProcessorManagerInterface
{
    use BasicServiceTrait;

    /**
     * @var array<string, array>
     */
    private array $processors = [];

    public function getProcessor(Component $component): ComponentProcessorInterface
    {
        $componentProcessorClass = $component[0];
        $componentName = $component[1];

        // Return the reference to the ItemProcessor instance, and created first if it doesn't exist
        if (!$this->hasItemBeenLoaded($component)) {
            // Get the instance from the InstanceManager
            $processorInstance = $this->getInstanceManager()->getInstance($componentProcessorClass);
            $this->processors[$componentProcessorClass][$componentName] = $processorInstance;
        }

        return $this->processors[$componentProcessorClass][$componentName];
    }

    protected function hasItemBeenLoaded(Component $component): bool
    {
        $componentProcessorClass = $component[0];
        $componentName = $component[1];
        return isset($this->processors[$componentProcessorClass][$componentName]);
    }
}
