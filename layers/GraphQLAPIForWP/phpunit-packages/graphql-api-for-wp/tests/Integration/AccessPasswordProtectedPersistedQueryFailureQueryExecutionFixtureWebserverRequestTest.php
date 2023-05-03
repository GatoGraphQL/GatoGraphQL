<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

class AccessPasswordProtectedPersistedQueryFailureQueryExecutionFixtureWebserverRequestTest extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use AccessPasswordProtectedPersistedQueryQueryExecutionFixtureWebserverRequestTestTrait;
    use AccessPasswordProtectedPersistedQueryFailsQueryExecutionFixtureWebserverRequestTestTrait;

    protected function viewSource(): bool
    {
        return false;
    }
}
