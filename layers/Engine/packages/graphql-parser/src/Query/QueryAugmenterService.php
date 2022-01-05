<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Query;

use PoP\Engine\App;
use PoP\Root\Managers\ComponentManager;
use PoP\GraphQLParser\Component;
use PoP\GraphQLParser\ComponentConfiguration;
use PoP\GraphQLParser\Query\ClientSymbols;

class QueryAugmenterService implements QueryAugmenterServiceInterface
{
    /**
     * Indicate if passing the operation name to support the "multiple query execution" feature
     */
    public function isExecutingAllOperations(string $operationName): bool
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponentManager()->getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->enableMultipleQueryExecution()
            && strtoupper($operationName) === ClientSymbols::GRAPHIQL_QUERY_BATCHING_OPERATION_NAME;
    }
}
