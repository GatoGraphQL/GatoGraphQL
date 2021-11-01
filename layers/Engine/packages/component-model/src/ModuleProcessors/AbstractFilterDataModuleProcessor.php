<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorManagerInterface;

abstract class AbstractFilterDataModuleProcessor extends AbstractModuleProcessor implements FilterDataModuleProcessorInterface
{
    use FilterDataModuleProcessorTrait;

    private ?FilterInputProcessorManagerInterface $filterInputProcessorManager = null;

    final public function setFilterInputProcessorManager(FilterInputProcessorManagerInterface $filterInputProcessorManager): void
    {
        $this->filterInputProcessorManager = $filterInputProcessorManager;
    }
    final protected function getFilterInputProcessorManager(): FilterInputProcessorManagerInterface
    {
        return $this->filterInputProcessorManager ??= $this->instanceManager->getInstance(FilterInputProcessorManagerInterface::class);
    }
}
