<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleDecoratorProcessors;

trait ModuleDecoratorProcessorManagerTrait
{
    /**
     * @var array<string, string>
     */
    public array $settings = [];
    /**
     * @var array<string, mixed>
     */
    public array $processordecorators = [];

    public function getProcessordecorator($processor)
    {
        $processordecorator = null;

        // If there's already a processordecorator for this module, then return it
        $processor_classname = get_class($processor);
        $processordecorator = $this->processordecorators[$processor_classname];

        // If not, build a new one from the settings, and assign it under the current processor
        if (!$processordecorator) {
            do {
                if ($processordecorator_classname = $this->settings[$processor_classname] ?? null) {
                    $processordecorator = new $processordecorator_classname($processor);
                    $this->processordecorators[$processor_classname] = $processordecorator;
                    break;
                }
            } while ($processor_classname = get_parent_class($processor_classname));
        }

        return $processordecorator;
    }

    public function add($processor_classname, $processordecorator_classname)
    {
        $this->settings[$processor_classname] = $processordecorator_classname;
    }
}
