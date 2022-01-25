<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLRequest\StaticHelpers;

class GraphQLQueryPayloadRetriever
{
    /**
     * Retrieve the GraphQL payload sent via POST, or `null` if none
     *
     * @return array<string,mixed>|null
     */
    public static function getGraphQLQueryPayload(): ?array
    {
        if (\PoP\Root\App::server('REQUEST_METHOD') !== 'POST') {
            return null;
        }

        // Attempt to get the query from the body, following the GraphQL syntax
        if (isset(\PoP\Root\App::server('CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE') === 'application/json') {
            $rawBody = file_get_contents('php://input');
            return json_decode($rawBody ?: '', true);
        }
        // Retrieve the entries from POST
        $payload = [];
        $entries = ['query', 'variables', 'operationName'];
        foreach ($entries as $entry) {
            if (array_key_exists($entry, $_POST)) {
                $payload[$entry] = $_POST[$entry];
            }
        }
        return $payload;
    }
}
