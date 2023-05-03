<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

/**
 * Test that only the schema editor user can visualize/execute
 * a Password-Protected Custom Endpoint
 */
abstract class AbstractAccessPasswordProtectedCustomEndpointQueryExecutionFixtureWebserverRequestTestCase extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use AccessPasswordProtectedPostWebserverRequestTestCaseTrait;
    use AccessPasswordProtectedCustomEndpointQueryExecutionFixtureWebserverRequestTestTrait;
}
