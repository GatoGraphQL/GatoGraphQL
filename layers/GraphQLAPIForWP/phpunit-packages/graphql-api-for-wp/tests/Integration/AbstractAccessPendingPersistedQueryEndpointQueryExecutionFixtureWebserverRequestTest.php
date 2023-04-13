<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

/**
 * Test that only the admin can access a pending persisted query
 */
abstract class AbstractAccessPendingPersistedQueryEndpointQueryExecutionFixtureWebserverRequestTest extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-pending-persisted-query-endpoint';
    }

    protected function getEndpoint(): string
    {
        /**
         * Can't use "graphql-query/pending-persisted-query/" as
         * the post is pending
         */
        return '?post_type=graphql-query&p=274';
    }
}
