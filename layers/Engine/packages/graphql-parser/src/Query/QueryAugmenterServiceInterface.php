<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Query;

use PoP\GraphQLParser\Spec\Parser\Ast\Variable;
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
     * @return OperationInterface[]|null
     */
    public function getMultipleQueryExecutionOperations(string $operationName, array $operations): ?array;

    /**
     * If referencing a variable that starts with "_", and the variable
     * has not been defined in the operation, then it's a dynamic variable
     */
    public function isDynamicVariableReference(
        string $name,
        ?Variable $variable,
    ): bool;

    /**
     * If referencing a variable that starts with "__", the variable
     * has not been defined in the operation, and there's a field
     * in the same query block, then it's a reference to the value
     * of the resolved field on the same object
     */
    public function isObjectResolvedFieldValueReference(
        string $name,
        ?Variable $variable,
    ): bool;

    /**
     * Actual name of the field (without the leading "__")
     */
    public function extractObjectResolvedFieldName(string $name): string;
}
