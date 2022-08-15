<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLRequest\Execution;

use GraphQLByPoP\GraphQLRequest\ObjectModels\GraphQLQueryPayload;

interface QueryRetrieverInterface
{
    public function extractRequestedGraphQLQueryPayload(): GraphQLQueryPayload;
}
