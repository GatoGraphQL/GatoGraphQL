<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ItemProcessors;

use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;

trait ItemProcessorManagerTrait
{
    /**
     * @var array<string, array>
     */
    private array $processors = [];
    /**
     * @var array<string, array>
     */
    private array $overridingClasses = [];
    /**
     * @var array<string, object>
     */
    private array $itemFullNameProcessorInstances = [];

    public function getLoadedItemFullNameProcessorInstances()
    {
        return $this->itemFullNameProcessorInstances;
    }

    public function getLoadedItems()
    {
        // Return a list of all loaded items
        return array_map(
            [ProcessorItemUtils::class, 'getItemFromFullName'],
            array_keys($this->itemFullNameProcessorInstances)
        );
    }

    public function overrideProcessorClass(string $overrideClass, string $withClass, array $forItemNames): void
    {
        foreach ($forItemNames as $forItemName) {
            $this->overridingClasses[$overrideClass][$forItemName] = $withClass;
        }
    }

    protected function hasItemBeenLoaded(array $item)
    {
        $itemProcessorClass = $item[0];
        $itemName = $item[1];
        return isset($this->processors[$itemProcessorClass][$itemName]);
    }

    public function getItemProcessor(array $item)
    {
        $itemProcessorClass = $item[0];
        $itemName = $item[1];

        // Return the reference to the ItemProcessor instance, and created first if it doesn't exist
        if (!$this->hasItemBeenLoaded($item)) {
            // If this class was overriden, use that one instead. Priority goes like this:
            // 1. Overriden
            // 3. Same class as requested
            if ($class = $this->overridingClasses[$itemProcessorClass][$itemName] ?? null) {
                $itemProcessorClass = $class;
            }

            // Get the instance from the InstanceManager
            $instanceManager = InstanceManagerFacade::getInstance();
            $processorInstance = $instanceManager->getInstance($itemProcessorClass);
            $this->processors[$itemProcessorClass][$itemName] = $processorInstance;

            // Keep a copy of what instance was generated for which item;
            $itemFullName = ProcessorItemUtils::getItemFullName($item);
            $this->itemFullNameProcessorInstances[$itemFullName] = $processorInstance;
        }

        return $this->processors[$itemProcessorClass][$itemName];
    }

    public function getProcessor(array $item)
    {
        return $this->getItemProcessor($item);
    }
}
