<?php

declare(strict_types=1);

namespace PoPAPI\API\HelperServices;

use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;

interface ApplicationStateFillerServiceInterface
{
    /**
     * Inject the GraphQL query AST and variables into
     * the app state.
     *
     * @param array<string,mixed> $variables
     */
    public function defineGraphQLQueryVarsInApplicationState(
        string|ExecutableDocument $queryOrExecutableDocument,
        array $variables = [],
        ?string $operationName = null
    ): void;
}
