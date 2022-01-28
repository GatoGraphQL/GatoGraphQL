<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Query;

use PoP\Root\App;
use PoP\GraphQLParser\Component;
use PoP\GraphQLParser\ComponentConfiguration;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;

class QueryAugmenterService implements QueryAugmenterServiceInterface
{
    /**
     * If passing operationName=__ALL return all operations in the document
     * (except __ALL). Otherwise return null.
     *
     * @param OperationInterface[] $operations
     * @return OperationInterface[]|null
     */
    public function getMultipleQueryExecutionOperations(string $operationName, array $operations): ?array
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        if (
            $componentConfiguration->enableMultipleQueryExecution()
            && strtoupper($operationName) === ClientSymbols::GRAPHIQL_QUERY_BATCHING_OPERATION_NAME
        ) {
            // Remove the __ALL operation, that's just a placeholder
            return array_values(array_filter(
                $operations,
                fn (OperationInterface $operation) => $operation->getName() !== ClientSymbols::GRAPHIQL_QUERY_BATCHING_OPERATION_NAME,
            ));
        }
        return null;
    }
}
