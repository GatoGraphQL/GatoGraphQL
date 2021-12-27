<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Execution;

use PoP\GraphQLParser\ComponentConfiguration;
use PoP\GraphQLParser\Query\ClientSymbols;
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
        if ($this->isExecutingAllOperations($this->operationName)) {
            return $this->document->getOperations();
        }
        
        return parent::getSelectedOperationsToExecute();
    }

    protected function isExecutingAllOperations(string $operationName): bool
    {
        return ComponentConfiguration::enableMultipleQueryExecution()
            && strtoupper($operationName) === ClientSymbols::GRAPHIQL_QUERY_BATCHING_OPERATION_NAME;
    }
}
