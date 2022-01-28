<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Query;

use PoP\GraphQLParser\Component;
use PoP\GraphQLParser\ComponentConfiguration;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\Root\App;

class QueryAugmenterService implements QueryAugmenterServiceInterface
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
    public function getMultipleQueryExecutionOperations(string $operationName, array $operations): ?array
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        if (!$componentConfiguration->enableMultipleQueryExecution()) {
            return null;
        }

        $nonAllOperations = array_values(array_filter(
            $operations,
            fn (OperationInterface $operation) => $operation->getName() !== ClientSymbols::GRAPHIQL_QUERY_BATCHING_OPERATION_NAME,
        ));

        if (
            // Passing operationName=__ALL
            strtoupper($operationName) === ClientSymbols::GRAPHIQL_QUERY_BATCHING_OPERATION_NAME
            // Passing no operationName and __ALL exists in the document
            || ($operationName === '' && count($operations) > count($nonAllOperations))
        ) {
            return $nonAllOperations;
        }
        return null;
    }
}
