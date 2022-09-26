<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Query;

use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;

interface QueryAugmenterServiceInterface
{
    /**
     * If passing operationName=__ALL inside the document in the body,
     * or if passing no operationName but __ALL is defined in the document,
     * then return all operations in the document (except __ALL).
     * Otherwise return null.
     *
     * @param OperationInterface[] $operations
     * @return OperationInterface[]
     */
    public function getMultipleQueryExecutionOperations(
        OperationInterface $requestedOperation,
        array $operations,
    ): array;
}
