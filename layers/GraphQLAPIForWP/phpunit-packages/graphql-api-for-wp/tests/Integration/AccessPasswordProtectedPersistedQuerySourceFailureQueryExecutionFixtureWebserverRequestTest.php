<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

class AccessPasswordProtectedPersistedQuerySourceFailureQueryExecutionFixtureWebserverRequestTest extends AbstractFixtureEndpointWebserverRequestTestCaseCase
{
    use AccessPasswordProtectedPersistedQueryQueryExecutionFixtureWebserverRequestTestTrait;
    use AccessPasswordProtectedPersistedQueryFailsQueryExecutionFixtureWebserverRequestTestTrait;

    protected function viewSource(): bool
    {
        return true;
    }
}
