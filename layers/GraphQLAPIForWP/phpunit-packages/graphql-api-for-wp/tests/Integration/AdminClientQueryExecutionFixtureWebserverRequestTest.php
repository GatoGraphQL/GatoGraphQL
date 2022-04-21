<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

class AdminClientQueryExecutionFixtureWebserverRequestTest extends AbstractAdminClientQueryExecutionFixtureWebserverRequestTestCase
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-admin-client-queries';
    }
}
