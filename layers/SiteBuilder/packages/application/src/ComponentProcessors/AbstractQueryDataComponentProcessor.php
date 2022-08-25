<?php

declare(strict_types=1);

namespace PoP\Application\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\ComponentProcessors\QueryDataComponentProcessorInterface;
use PoP\ComponentModel\ComponentProcessors\QueryDataComponentProcessorTrait;
use PoP\ComponentModel\QueryInputOutputHandlers\ActionExecutionQueryInputOutputHandler;
use PoP\Root\Feedback\FeedbackItemResolution;

abstract class AbstractQueryDataComponentProcessor extends AbstractComponentProcessor implements QueryDataComponentProcessorInterface
{
    use QueryDataComponentProcessorTrait;

    private ?ActionExecutionQueryInputOutputHandler $actionExecutionQueryInputOutputHandler = null;

    final public function setActionExecutionQueryInputOutputHandler(ActionExecutionQueryInputOutputHandler $actionExecutionQueryInputOutputHandler): void
    {
        $this->actionExecutionQueryInputOutputHandler = $actionExecutionQueryInputOutputHandler;
    }
    final protected function getActionExecutionQueryInputOutputHandler(): ActionExecutionQueryInputOutputHandler
    {
        /** @var ActionExecutionQueryInputOutputHandler */
        return $this->actionExecutionQueryInputOutputHandler ??= $this->instanceManager->getInstance(ActionExecutionQueryInputOutputHandler::class);
    }

    /**
     * @return mixed[]
     * @param array<string,mixed> $props
     * @param array<string,mixed> $data_properties
     * @param string|int|array<string|int> $objectIDOrIDs
     * @param array<string,mixed>|null $executed
     */
    public function getDatasetmeta(
        Component $component,
        array &$props,
        array $data_properties,
        ?FeedbackItemResolution $dataaccess_checkpoint_validation,
        ?FeedbackItemResolution $actionexecution_checkpoint_validation,
        ?array $executed,
        string|int|array $objectIDOrIDs,
    ): array {
        $ret = parent::getDatasetmeta($component, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDOrIDs);

        $ret = $this->addQueryHandlerDatasetmeta($ret, $component, $props, $data_properties, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $objectIDOrIDs);

        return $ret;
    }
}
