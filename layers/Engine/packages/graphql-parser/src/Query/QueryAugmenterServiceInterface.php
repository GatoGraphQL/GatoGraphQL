<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Query;

use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;

interface QueryAugmenterServiceInterface
{
    /**
     * If passing operationName=__ALL return all operations in the document
     * (except __ALL). Otherwise return null.
     *
     * @param OperationInterface[] $operations
     * @return OperationInterface[]|null
     */
    public function getMultipleQueryExecutionOperations(string $operationName, array $operations): ?array;
}
