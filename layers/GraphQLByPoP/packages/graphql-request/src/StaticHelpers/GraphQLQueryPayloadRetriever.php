<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLRequest\StaticHelpers;

use PoP\Root\App;
use stdClass;

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
            if ($rawBody === false) {
                return null;
            }
            $decodedJSON = json_decode($rawBody);
            if (!is_array($decodedJSON) && !($decodedJSON instanceof stdClass)) {
                return null;
            }
            $json = (object) $decodedJSON;
            $payload = [
                'query' => $json->query,
            ];
            if (isset($json->variables)) {
                $payload['variables'] = (array) $json->variables;
            }
            if (isset($json->operationName)) {
                $payload['operationName'] = $json->operationName;
            }
            return $payload;
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
