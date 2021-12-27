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
     * @param OperationInterface[] $operations
     * @return OperationInterface[]
     */
    protected function getSelectedOperationsToExecute(array $operations, string $operationName): array
    {
        if (ComponentConfiguration::enableMultipleQueryExecution()
            && $this->operationName === ClientSymbols::GRAPHIQL_QUERY_BATCHING_OPERATION_NAME
        ) {
            return $operations;
        }
        
        return parent::getSelectedOperationsToExecute($operations, $operationName);
    }
}
