<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLRequest\Execution;

use GraphQLByPoP\GraphQLRequest\ObjectModels\GraphQLQueryPayload;
use GraphQLByPoP\GraphQLRequest\StaticHelpers\GraphQLQueryPayloadRetriever;

class QueryRetriever implements QueryRetrieverInterface
{
    public function extractRequestedGraphQLQueryPayload(): GraphQLQueryPayload
    {
        // Attempt to get the query from the body, following the GraphQL syntax
        $payload = GraphQLQueryPayloadRetriever::getGraphQLQueryPayload();
        if ($payload === null) {
            return new GraphQLQueryPayload(null, null, null);
        }
        return new GraphQLQueryPayload(
            $payload['query'] ?? null,
            $payload['variables'] ?? null,
            $payload['operationName'] ?? null,
        );
    }
}
