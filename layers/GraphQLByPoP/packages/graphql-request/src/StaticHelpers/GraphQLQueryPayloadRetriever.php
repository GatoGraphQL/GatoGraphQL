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
            $payload = [];
            if (isset($json->query)) {
                $payload['query'] = $json->query;
            }
            if (isset($json->variables)) {
                $payload['variables'] = (array) $json->variables;
            }
            if (isset($json->operationName)) {
                $payload['operationName'] = $json->operationName;
            }
            return static::maybeAddPayloadDataFromGET($payload);
        }

        // Retrieve the entries from POST
        $payload = [];
        $entries = ['query', 'variables', 'operationName'];
        $request = App::getRequest()->request;
        foreach ($entries as $entry) {
            if (!$request->has($entry)) {
                continue;
            }
            $payload[$entry] = App::request($entry);
        }
        return static::maybeAddPayloadDataFromGET($payload);
    }

    /**
     * ?operationName=... can be passed as a GET param in the URL,
     * eg: to execute a Persisted Query while still passing a body via POST
     *
     * @param array<string,mixed> $payload
     * @return array<string,mixed>
     */
    protected static function maybeAddPayloadDataFromGET(array $payload): array
    {
        if (!isset($payload['operationName']) && App::query('operationName') !== null) {
            $payload['operationName'] = App::query('operationName');
        }
        return $payload;
    }
}
