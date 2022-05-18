<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorManagerInterface;

abstract class AbstractFilterDataComponentProcessor extends AbstractComponentProcessor implements FilterDataComponentProcessorInterface
{
    use FilterDataComponentProcessorTrait;

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
