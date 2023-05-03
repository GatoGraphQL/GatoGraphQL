<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

class AdminClientQueryExecutionFixtureWebserverRequestTest extends AbstractAdminClientQueryExecutionFixtureWebserverRequestTestCaseCase
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-introspection-and-config';
    }

    protected function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-admin-client';
    }
}
