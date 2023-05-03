<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

class SingleEndpointQueryExecutionFixtureWebserverRequestTest extends AbstractAdminClientQueryExecutionFixtureWebserverRequestTestCaseCase
{
    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-single-endpoint';
    }
}
