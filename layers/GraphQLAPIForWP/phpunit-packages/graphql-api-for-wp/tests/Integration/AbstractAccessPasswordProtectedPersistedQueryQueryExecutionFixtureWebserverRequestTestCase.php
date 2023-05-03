<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

/**
 * Test that only the schema editor user can visualize/execute
 * a Password-Protected Persisted Query
 */
abstract class AbstractAccessPasswordProtectedPersistedQueryQueryExecutionFixtureWebserverRequestTestCase extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use AccessPasswordProtectedPostWebserverRequestTestCaseTrait;
    use AccessPasswordProtectedPersistedQueryQueryExecutionFixtureWebserverRequestTestTrait;
}
