<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLRequest\Execution;

use GraphQLByPoP\GraphQLRequest\StaticHelpers\GraphQLQueryPayloadRetriever;

class QueryRetriever implements QueryRetrieverInterface
{
    /**
     * @return array<?string> 3 items: [query, variables, operationName]
     */
    public function extractRequestedGraphQLQueryPayload(): array
    {
        // Attempt to get the query from the body, following the GraphQL syntax
        $payload = GraphQLQueryPayloadRetriever::getGraphQLQueryPayload();
        if ($payload === null) {
            return [null, null, null];
        }
        return [
            $payload['query'] ?? null,
            $payload['variables'] ?? null,
            $payload['operationName'] ?? null,
        ];
    }
}
