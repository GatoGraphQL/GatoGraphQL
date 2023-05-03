<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

class AccessPrivateCustomEndpointByAdminQueryExecutionFixtureWebserverRequestTest extends AbstractAccessPrivateCustomEndpointQueryExecutionFixtureWebserverRequestTestCase
{
    protected function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-private-custom-endpoints-success';
    }

    protected function accessClient(): bool
    {
        return false;
    }
}
