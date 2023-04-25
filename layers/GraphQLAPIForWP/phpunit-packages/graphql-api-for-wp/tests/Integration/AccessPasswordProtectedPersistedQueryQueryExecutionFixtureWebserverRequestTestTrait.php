<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

/**
 * Test that only the schema editor user can visualize/execute
 * a Password-Protected Persisted Query
 */
trait AccessPasswordProtectedPersistedQueryQueryExecutionFixtureWebserverRequestTestTrait
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-password-protected-persisted-queries';
    }

    protected function getEndpoint(): string
    {
        return sprintf(
            'graphql-query/password-protected-persisted-query/%s',
            $this->viewSource()
                ? '?view=source'
                : ''
        );
    }

    abstract protected function viewSource(): bool;
}
