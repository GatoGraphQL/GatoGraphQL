<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

/**
 * Test that only the schema editor user can visualize/execute
 * a Private Custom Endpoint
 */
abstract class AbstractAccessPrivateCustomEndpointQueryExecutionFixtureWebserverRequestTestCase extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use WordPressAuthenticatedUserWebserverRequestTestCaseTrait;
    use AccessPrivateCustomEndpointQueryExecutionFixtureWebserverRequestTestTrait;
}
