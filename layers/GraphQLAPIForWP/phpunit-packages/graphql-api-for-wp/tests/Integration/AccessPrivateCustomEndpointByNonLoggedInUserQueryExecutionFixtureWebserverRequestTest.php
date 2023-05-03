<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

class AccessPrivateCustomEndpointByNonLoggedInUserQueryExecutionFixtureWebserverRequestTest extends AbstractFixtureEndpointWebserverRequestTestCaseCase
{
    use AccessPrivateCustomEndpointQueryExecutionFixtureWebserverRequestTestTrait;
    use AccessPrivateCustomEndpointFailsQueryExecutionFixtureWebserverRequestTestTrait;

    protected function accessClient(): bool
    {
        return false;
    }
}
