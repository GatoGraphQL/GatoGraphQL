<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\ModuleProcessors;

use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorManagerInterface;
use PoP\ComponentModel\ModuleProcessors\QueryDataModuleProcessorInterface;
use PoP\ComponentModel\ModuleProcessors\QueryDataModuleProcessorTrait;

abstract class AbstractQueryDataModuleProcessor extends AbstractModuleProcessor implements QueryDataModuleProcessorInterface
{
    use QueryDataModuleProcessorTrait;

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
