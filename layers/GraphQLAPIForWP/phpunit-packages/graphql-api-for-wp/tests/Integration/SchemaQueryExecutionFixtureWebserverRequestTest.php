<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

class SchemaQueryExecutionFixtureWebserverRequestTest extends AbstractAdminClientQueryExecutionFixtureWebserverRequestTestCase
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-schema';
    }
}
