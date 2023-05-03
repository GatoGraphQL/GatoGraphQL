<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

class AccessPendingPersistedQueryEndpointByAdminQueryExecutionFixtureWebserverRequestTest extends AbstractAccessPendingPersistedQueryEndpointQueryExecutionFixtureWebserverRequestTestCase
{
    protected function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-pending-persisted-query-endpoint-by-admin';
    }
}
