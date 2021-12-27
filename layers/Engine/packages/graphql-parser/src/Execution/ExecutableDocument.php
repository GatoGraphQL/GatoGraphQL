<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Execution;

use GraphQLByPoP\GraphQLQuery\Schema\ClientSymbols;
use PoPBackbone\GraphQLParser\Execution\ExecutableDocument as UpstreamExecutableDocument;

class ExecutableDocument extends UpstreamExecutableDocument
{
    /**
     * Override to support the "multiple query execution" feature.
     *
     * @param OperationInterface[] $operations
     * @return OperationInterface[]
     */
    protected function getSelectedOperationsToExecute(array $operations, string $operationName): array
    {
        // Executing `__ALL`?
        if ($this->operationName === ClientSymbols::GRAPHIQL_QUERY_BATCHING_OPERATION_NAME) {
            return $operations;
        }
        
        return parent::getSelectedOperationsToExecute($operations, $operationName);
    }
}
