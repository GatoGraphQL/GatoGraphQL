<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

/**
 * Test that only the schema editor user can visualize/execute
 * a Private Custom Endpoint
 */
abstract class AbstractAccessPasswordProtectedCustomEndpointQueryExecutionFixtureWebserverRequestTest extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use AccessPasswordProtectedPostWebserverRequestTestCaseTrait;
    use AccessPasswordProtectedCustomEndpointQueryExecutionFixtureWebserverRequestTestTrait;
}
