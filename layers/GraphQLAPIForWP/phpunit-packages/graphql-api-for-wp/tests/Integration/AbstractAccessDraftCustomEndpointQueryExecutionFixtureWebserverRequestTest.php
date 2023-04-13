<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

/**
 * Test that only the admin can access a draft custom endpoint
 */
abstract class AbstractAccessDraftCustomEndpointQueryExecutionFixtureWebserverRequestTest extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-draft-custom-endpoint';
    }

    protected function getEndpoint(): string
    {
        /**
         * Using the "draft" endpoint (otherwise
         * it should be "graphql/draft-custom-endpoint/")
         */
        return '?post_type=graphql-endpoint&p=308&preview=true';
    }
}
