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
    /**
     * @var array<string, array>
     */
    private array $overridingClasses = [];

    public function getProcessor(Component $component): ComponentProcessorInterface
    {
        $componentProcessorClass = $component[0];
        $componentName = $component[1];

        // Return the reference to the ItemProcessor instance, and created first if it doesn't exist
        if (!$this->hasItemBeenLoaded($component)) {
            // If this class was overriden, use that one instead. Priority goes like this:
            // 1. Overriden
            // 3. Same class as requested
            if ($class = $this->overridingClasses[$componentProcessorClass][$componentName] ?? null) {
                $componentProcessorClass = $class;
            }

            // Get the instance from the InstanceManager
            $processorInstance = $this->getInstanceManager()->getInstance($componentProcessorClass);
            $this->processors[$componentProcessorClass][$componentName] = $processorInstance;
        }

        return $this->processors[$componentProcessorClass][$componentName];
    }

    /**
     * @deprecated Use the Service Container instead
     */
    public function overrideProcessorClass(string $overrideClass, string $withClass, array $forItemNames): void
    {
        foreach ($forItemNames as $forItemName) {
            $this->overridingClasses[$overrideClass][$forItemName] = $withClass;
        }
    }

    protected function hasItemBeenLoaded(Component $component): bool
    {
        $componentProcessorClass = $component[0];
        $componentName = $component[1];
        return isset($this->processors[$componentProcessorClass][$componentName]);
    }
}
