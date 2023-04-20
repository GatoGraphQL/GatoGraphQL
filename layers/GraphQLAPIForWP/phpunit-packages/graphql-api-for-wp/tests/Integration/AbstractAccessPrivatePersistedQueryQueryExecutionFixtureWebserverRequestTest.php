<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

/**
 * Test that only the schema editor user can visualize/execute
 * a Private Persisted Query
 */
abstract class AbstractAccessPrivatePersistedQueryQueryExecutionFixtureWebserverRequestTest extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use WordPressAuthenticatedUserWebserverRequestTestCaseTrait;
    use AccessPrivatePersistedQueryQueryExecutionFixtureWebserverRequestTestTrait;
}
