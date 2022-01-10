<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLRequest\Execution;

class QueryRetriever implements QueryRetrieverInterface
{
    /**
     * @return array<?string> 3 items: [query, variables, operationName]
     */
    public function extractRequestedGraphQLQueryPayload(): array
    {
        // Attempt to get the query from the body, following the GraphQL syntax
        if (isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] === 'application/json') {
            $rawBody     = file_get_contents('php://input');
            $payload = json_decode($rawBody ?: '', true);
        } else {
            $payload = $_POST;
        }
        return [
            $payload['query'] ?? null,
            $payload['variables'] ?? null,
            $payload['operationName'] ?? null
        ];
    }
}
