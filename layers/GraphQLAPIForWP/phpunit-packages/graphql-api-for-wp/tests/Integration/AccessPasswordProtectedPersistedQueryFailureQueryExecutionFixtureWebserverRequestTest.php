<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

class AccessPasswordProtectedPersistedQueryFailureQueryExecutionFixtureWebserverRequestTest extends AbstractFixtureEndpointWebserverRequestTestCaseCase
{
    use AccessPasswordProtectedPersistedQueryQueryExecutionFixtureWebserverRequestTestTrait;
    use AccessPasswordProtectedPersistedQueryFailsQueryExecutionFixtureWebserverRequestTestTrait;

    protected function viewSource(): bool
    {
        return false;
    }
}
