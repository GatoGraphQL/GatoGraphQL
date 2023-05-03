<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

class AccessPrivatePersistedQueryByNonLoggedInUserQueryExecutionFixtureWebserverRequestTest extends AbstractFixtureEndpointWebserverRequestTestCaseCase
{
    use AccessPrivatePersistedQueryQueryExecutionFixtureWebserverRequestTestTrait;
    use AccessPrivatePersistedQueryFailsQueryExecutionFixtureWebserverRequestTestTrait;

    protected function viewSource(): bool
    {
        return false;
    }
}
