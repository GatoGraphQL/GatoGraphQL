<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\QueryInputOutputHandlers\ActionExecutionQueryInputOutputHandler;

abstract class AbstractQueryDataComponentProcessor extends AbstractFilterDataComponentProcessor implements QueryDataComponentProcessorInterface
{
    use QueryDataComponentProcessorTrait;

    private ?ActionExecutionQueryInputOutputHandler $actionExecutionQueryInputOutputHandler = null;

    final public function setActionExecutionQueryInputOutputHandler(ActionExecutionQueryInputOutputHandler $actionExecutionQueryInputOutputHandler): void
    {
        $this->actionExecutionQueryInputOutputHandler = $actionExecutionQueryInputOutputHandler;
    }
    final protected function getActionExecutionQueryInputOutputHandler(): ActionExecutionQueryInputOutputHandler
    {
        return $this->actionExecutionQueryInputOutputHandler ??= $this->instanceManager->getInstance(ActionExecutionQueryInputOutputHandler::class);
    }
}
