<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\FilterInputs\FilterInputManagerInterface;

abstract class AbstractFilterDataComponentProcessor extends AbstractComponentProcessor implements FilterDataComponentProcessorInterface
{
    use FilterDataComponentProcessorTrait;

    private ?FilterInputManagerInterface $filterInputProcessorManager = null;

    final public function setFilterInputManager(FilterInputManagerInterface $filterInputProcessorManager): void
    {
        $this->filterInputProcessorManager = $filterInputProcessorManager;
    }
    final protected function getFilterInputManager(): FilterInputManagerInterface
    {
        return $this->filterInputProcessorManager ??= $this->instanceManager->getInstance(FilterInputManagerInterface::class);
    }
}
