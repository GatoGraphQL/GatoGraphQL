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
        return 'private-query/comments-from-this-month/';
    }
}
