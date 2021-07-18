<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLRequest\Execution;

interface QueryRetrieverInterface
{
    /**
     * @return array<?string> 3 items: [query, variables, operationName]
     */
    public function extractRequestedGraphQLQueryPayload(): array;
}
