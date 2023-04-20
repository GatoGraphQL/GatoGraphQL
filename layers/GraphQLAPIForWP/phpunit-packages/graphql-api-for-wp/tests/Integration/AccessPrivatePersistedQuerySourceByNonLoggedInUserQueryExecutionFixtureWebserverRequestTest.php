<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

class AccessPrivatePersistedQuerySourceByNonLoggedInUserQueryExecutionFixtureWebserverRequestTest extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use AccessPrivatePersistedQueryQueryExecutionFixtureWebserverRequestTestTrait;
    use AccessPrivatePersistedQueryFailsQueryExecutionFixtureWebserverRequestTestTrait;

    protected function viewSource(): bool
    {
        return true;
    }
}
