<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLRequest\StaticHelpers;

use PoP\Root\App;

class GraphQLQueryPayloadRetriever
{
    /**
     * Retrieve the GraphQL payload sent via POST, or `null` if none
     *
     * @return array<string,mixed>|null
     */
    public static function getGraphQLQueryPayload(): ?array
    {
        if (App::server('REQUEST_METHOD') !== 'POST') {
            return null;
        }

        // Attempt to get the query from the body, following the GraphQL syntax
        if (App::server('CONTENT_TYPE') === 'application/json') {
            $rawBody = file_get_contents('php://input');
            return json_decode($rawBody ?: '', true);
        }

        // Retrieve the entries from POST
        $payload = [];
        $entries = ['query', 'variables', 'operationName'];
        $request = App::getRequest()->request;
        foreach ($entries as $entry) {
            if ($request->has($entry)) {
                $payload[$entry] = App::request($entry);
            }
        }
        return $payload;
    }
}
