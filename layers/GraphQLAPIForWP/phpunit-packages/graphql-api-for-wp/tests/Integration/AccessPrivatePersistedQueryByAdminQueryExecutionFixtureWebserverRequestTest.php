<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

/**
 * Temporarily disabled by making the class abstract.
 *
 * @todo Re-enable when "Private Persisted Queries" are supported again.
 */
abstract class AccessPrivatePersistedQueryByAdminQueryExecutionFixtureWebserverRequestTest extends AbstractAccessPrivatePersistedQueryQueryExecutionFixtureWebserverRequestTest
{
    protected function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-private-persisted-queries-success';
    }

    protected function viewSource(): bool
    {
        return false;
    }
}
