<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Query;

use PoP\GraphQLParser\Module;
use PoP\GraphQLParser\ModuleConfiguration;
use PoP\GraphQLParser\ExtendedSpec\Constants\QuerySymbols;
use PoP\GraphQLParser\ExtendedSpec\Constants\QuerySyntax;
use PoP\GraphQLParser\Spec\Parser\Ast\Variable;
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
        $componentConfiguration = App::getComponent(Module::class)->getConfiguration();
        if (!$componentConfiguration->enableMultipleQueryExecution()) {
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

    public function isDynamicVariableReference(
        string $name,
        ?Variable $variable,
    ): bool {
        /** @var ModuleConfiguration */
        $componentConfiguration = App::getComponent(Module::class)->getConfiguration();
        if (!$componentConfiguration->enableDynamicVariables()) {
            return false;
        }

        return $variable === null
            && \str_starts_with(
                $name,
                QuerySyntax::DYNAMIC_VARIABLE_NAME_PREFIX
            );
    }

    /**
     * Actual name of the dynamic variable (without the leading "_")
     */
    public function extractDynamicVariableName(string $name): string
    {
        return substr(
            $name,
            strlen(QuerySyntax::DYNAMIC_VARIABLE_NAME_PREFIX)
        );
    }
}
