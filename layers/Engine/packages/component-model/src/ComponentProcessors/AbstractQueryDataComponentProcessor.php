<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ComponentProcessors;

use PoP\ComponentModel\Component\Component;
use PoP\ComponentModel\QueryInputOutputHandlers\ActionExecutionQueryInputOutputHandler;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;

abstract class AbstractQueryDataComponentProcessor extends AbstractFilterDataComponentProcessor implements QueryDataComponentProcessorInterface
{
    use QueryDataComponentProcessorTrait;

    private ?ActionExecutionQueryInputOutputHandler $actionExecutionQueryInputOutputHandler = null;

    final protected function getActionExecutionQueryInputOutputHandler(): ActionExecutionQueryInputOutputHandler
    {
        if ($this->actionExecutionQueryInputOutputHandler === null) {
            /** @var ActionExecutionQueryInputOutputHandler */
            $actionExecutionQueryInputOutputHandler = $this->instanceManager->getInstance(ActionExecutionQueryInputOutputHandler::class);
            $this->actionExecutionQueryInputOutputHandler = $actionExecutionQueryInputOutputHandler;
        }
        return $this->actionExecutionQueryInputOutputHandler;
    }

    /**
     * @param array<string,mixed> $props
     * @param array<string,mixed> $data_properties
     * @param string|int|array<string|int> $objectIDOrIDs
     * @param array<string,mixed>|null $executed
     * @return array<string,mixed>
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
