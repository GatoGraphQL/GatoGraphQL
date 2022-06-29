<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Standalone;

use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\Root\HttpFoundation\Response;

interface GraphQLServerInterface
{
    /**
     * Execute a GraphQL query, print the response in the buffer,
     * and send headers (eg: content-type => "application/json")
     *
     * @param array<string,mixed> $variables
     */
    public function execute(
        string|ExecutableDocument $queryOrExecutableDocument,
        array $variables = [],
        ?string $operationName = null
    ): Response;
}
