<?php

declare(strict_types=1);

namespace PoP\Application\ComponentProcessors;

use PoP\ComponentModel\FilterInputs\FilterInputManagerInterface;
use PoP\ComponentModel\ComponentProcessors\QueryDataComponentProcessorInterface;
use PoP\ComponentModel\ComponentProcessors\QueryDataComponentProcessorTrait;
use PoP\ComponentModel\QueryInputOutputHandlers\ActionExecutionQueryInputOutputHandler;

abstract class AbstractQueryDataComponentProcessor extends AbstractComponentProcessor implements QueryDataComponentProcessorInterface
{
    use QueryDataComponentProcessorTrait;

    private ?FilterInputManagerInterface $filterInputProcessorManager = null;
    private ?ActionExecutionQueryInputOutputHandler $actionExecutionQueryInputOutputHandler = null;

    final public function setFilterInputManager(FilterInputManagerInterface $filterInputProcessorManager): void
    {
        $this->filterInputProcessorManager = $filterInputProcessorManager;
    }
    final protected function getFilterInputManager(): FilterInputManagerInterface
    {
        return $this->filterInputProcessorManager ??= $this->instanceManager->getInstance(FilterInputManagerInterface::class);
    }
    final public function setActionExecutionQueryInputOutputHandler(ActionExecutionQueryInputOutputHandler $actionExecutionQueryInputOutputHandler): void
    {
        $this->actionExecutionQueryInputOutputHandler = $actionExecutionQueryInputOutputHandler;
    }
    final protected function getActionExecutionQueryInputOutputHandler(): ActionExecutionQueryInputOutputHandler
    {
        return $this->actionExecutionQueryInputOutputHandler ??= $this->instanceManager->getInstance(ActionExecutionQueryInputOutputHandler::class);
    }
}
