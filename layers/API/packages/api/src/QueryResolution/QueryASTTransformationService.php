<?php

declare(strict_types=1);

namespace PoPAPI\API\QueryResolution;

use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;

class QueryASTTransformationService implements QueryASTTransformationServiceInterface
{
    /**
     * @param OperationInterface[] $operations
     * @return OperationInterface[]
     */
    public function convertOperationsForMultipleQueryExecution(array $operations): array
    {
        return $operations;
    }
}
