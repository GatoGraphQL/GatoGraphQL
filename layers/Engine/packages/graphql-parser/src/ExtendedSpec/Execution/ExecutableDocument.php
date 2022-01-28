<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Execution;

use PoP\GraphQLParser\Component;
use PoP\GraphQLParser\ComponentConfiguration;
use PoP\GraphQLParser\Facades\Query\QueryAugmenterServiceFacade;
use PoP\GraphQLParser\Spec\Execution\ExecutableDocument as UpstreamExecutableDocument;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\Root\App;

class ExecutableDocument extends UpstreamExecutableDocument
{
    /**
     * @return OperationInterface[]
     * @throws InvalidRequestException
     */
    protected function getNonRequestedOperation(): array
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        if (!$componentConfiguration->enableMultipleQueryExecution()) {
            return parent::getNonRequestedOperation();
        }

        if (count($this->document->getOperations()) === 1) {
            return parent::getNonRequestedOperation();
        }

        $queryAugmenterService = QueryAugmenterServiceFacade::getInstance();
        $multipleQueryExecutionOperations = $queryAugmenterService->getMultipleQueryExecutionOperations($this->context->getOperationName(), $this->document->getOperations());
        if ($multipleQueryExecutionOperations !== null) {
            return $multipleQueryExecutionOperations;
        }

        return parent::getNonRequestedOperation();
    }

    /**
     * Override to support the "multiple query execution" feature:
     * If passing operation name `__ALL`, then execute all operations (hack)
     *
     * @return OperationInterface[]
     */
    protected function extractRequestedOperations(): array
    {
        $queryAugmenterService = QueryAugmenterServiceFacade::getInstance();
        $multipleQueryExecutionOperations = $queryAugmenterService->getMultipleQueryExecutionOperations($this->context->getOperationName(), $this->document->getOperations());
        if ($multipleQueryExecutionOperations !== null) {
            return $multipleQueryExecutionOperations;
        }

        return parent::extractRequestedOperations();
    }
}
