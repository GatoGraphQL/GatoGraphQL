<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\ModuleDecoratorProcessors;

trait ModuleDecoratorProcessorManagerTrait
{
    /**
     * @var array<string, string>
     */
    public array $settings = [];
    /**
     * @var array<string, mixed>
     */
    public array $processorDecorators = [];

    public function getProcessorDecorator($processor)
    {
        $processorDecorator = null;

        // If there's already a processorDecorator for this module, then return it
        $processorClass = get_class($processor);
        $processorDecorator = $this->processorDecorators[$processorClass];

        // If not, build a new one from the settings, and assign it under the current processor
        if (!$processorDecorator) {
            do {
                if ($processorDecoratorClass = $this->settings[$processorClass] ?? null) {
                    $processorDecorator = new $processorDecoratorClass($processor);
                    $this->processorDecorators[$processorClass] = $processorDecorator;
                    break;
                }
            } while ($processorClass = get_parent_class($processorClass));
        }

        return $processorDecorator;
    }

    public function add($processorClass, $processorDecoratorClass)
    {
        $this->settings[$processorClass] = $processorDecoratorClass;
    }
}
