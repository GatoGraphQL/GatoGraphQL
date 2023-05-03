<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

class AccessPasswordProtectedCustomEndpointClientFailureQueryExecutionFixtureWebserverRequestTest extends AbstractFixtureEndpointWebserverRequestTestCaseCase
{
    use AccessPasswordProtectedCustomEndpointQueryExecutionFixtureWebserverRequestTestTrait;
    use AccessPasswordProtectedCustomEndpointFailsQueryExecutionFixtureWebserverRequestTestTrait;

    protected function accessClient(): bool
    {
        return true;
    }
}
