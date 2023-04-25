<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

/**
 * Test that only the schema editor user can visualize/execute
 * a Private Persisted Query
 */
trait AccessPrivatePersistedQueryQueryExecutionFixtureWebserverRequestTestTrait
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-private-persisted-queries';
    }

    protected function getEndpoint(): string
    {
        return sprintf(
            'graphql-query/comments-from-this-month/%s',
            $this->viewSource()
                ? '?view=source'
                : ''
        );
    }

    abstract protected function viewSource(): bool;
}
