<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\ComponentProcessors;

use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorManagerInterface;
use PoP\ComponentModel\ComponentProcessors\QueryDataComponentProcessorInterface;
use PoP\ComponentModel\ComponentProcessors\QueryDataComponentProcessorTrait;
use PoP\ComponentModel\QueryInputOutputHandlers\ActionExecutionQueryInputOutputHandler;

abstract class AbstractQueryDataComponentProcessor extends AbstractComponentProcessor implements QueryDataComponentProcessorInterface
{
    use QueryDataComponentProcessorTrait;

    private ?FilterInputProcessorManagerInterface $filterInputProcessorManager = null;
    private ?ActionExecutionQueryInputOutputHandler $actionExecutionQueryInputOutputHandler = null;

    final public function setFilterInputProcessorManager(FilterInputProcessorManagerInterface $filterInputProcessorManager): void
    {
        $this->filterInputProcessorManager = $filterInputProcessorManager;
    }
    final protected function getFilterInputProcessorManager(): FilterInputProcessorManagerInterface
    {
        return $this->filterInputProcessorManager ??= $this->instanceManager->getInstance(FilterInputProcessorManagerInterface::class);
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
