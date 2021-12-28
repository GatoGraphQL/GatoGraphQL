<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Query;

use PoP\GraphQLParser\ComponentConfiguration;
use PoP\GraphQLParser\Query\ClientSymbols;

class QueryAugmenterService implements QueryAugmenterServiceInterface
{
    /**
     * Indicate if passing the operation name to support the "multiple query execution" feature
     */
    public function isExecutingAllOperations(string $operationName): bool
    {
        return ComponentConfiguration::enableMultipleQueryExecution()
            && strtoupper($operationName) === ClientSymbols::GRAPHIQL_QUERY_BATCHING_OPERATION_NAME;
    }
}
