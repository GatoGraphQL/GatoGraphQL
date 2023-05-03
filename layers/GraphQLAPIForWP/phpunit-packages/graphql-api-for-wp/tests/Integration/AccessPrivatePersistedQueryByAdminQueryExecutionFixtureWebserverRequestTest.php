<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

class AccessPrivatePersistedQueryByAdminQueryExecutionFixtureWebserverRequestTest extends AbstractAccessPrivatePersistedQueryQueryExecutionFixtureWebserverRequestTestCase
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
