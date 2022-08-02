<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Query;

use PoP\GraphQLParser\Module;
use PoP\GraphQLParser\ModuleConfiguration;
use PoP\GraphQLParser\ExtendedSpec\Constants\QuerySymbols;
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
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->enableMultipleQueryExecution()) {
            return null;
        }

        $nonAllOperations = array_values(array_filter(
            $operations,
            fn (OperationInterface $operation) => $operation->getName() !== QuerySymbols::GRAPHIQL_QUERY_BATCHING_OPERATION_NAME,
        ));
        if (
            // Passing operationName=__ALL
            strtoupper($operationName) === QuerySymbols::GRAPHIQL_QUERY_BATCHING_OPERATION_NAME
            // Passing no operationName and __ALL exists in the document
            || ($operationName === '' && count($operations) > count($nonAllOperations))
        ) {
            return $nonAllOperations;
        }
        return null;
    }
}
