<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\WebserverRequests\WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

abstract class AbstractAdminPersistedQueryQueryExecutionFixtureWebserverRequestTestCase extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

    /**
     * Admin persisted query endpoint
     */
    protected function getEndpoint(): string
    {
        return 'wp-admin/edit.php?page=graphql_api&action=execute_query&endpoint_group=persistedQuery&persisted_query_id=65';
    }
}
