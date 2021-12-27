<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Execution;

use PoP\GraphQLParser\Facades\Query\QueryAugmenterServiceFacade;
use PoPBackbone\GraphQLParser\Execution\ExecutableDocument as UpstreamExecutableDocument;

class ExecutableDocument extends UpstreamExecutableDocument
{
    /**
     * Override to support the "multiple query execution" feature:
     * If passing operation name `__ALL`, then execute all operations (hack)
     *
     * @return OperationInterface[]
     */
    protected function getSelectedOperationsToExecute(): array
    {
        $queryAugmenterService = QueryAugmenterServiceFacade::getInstance();
        if ($queryAugmenterService->isExecutingAllOperations($this->operationName)) {
            return $this->document->getOperations();
        }
        
        return parent::getSelectedOperationsToExecute();
    }
}
