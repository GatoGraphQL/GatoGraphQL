<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLRequest\Execution;

class QueryExecutionHelpers
{
    public static function extractRequestedGraphQLQueryPayload()
    {
        // Attempt to get the query from the body, following the GraphQL syntax
        if (isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] === 'application/json') {
            $rawBody     = file_get_contents('php://input');
            $payload = json_decode($rawBody ?: '', true);
        } else {
            $payload = $_POST;
        }
        // Get the query, transform it, and set it on $vars
        return [
            $payload['query'] ?? null,
            $payload['variables'] ?? null,
            $payload['operationName'] ?? null
        ];
    }
}
