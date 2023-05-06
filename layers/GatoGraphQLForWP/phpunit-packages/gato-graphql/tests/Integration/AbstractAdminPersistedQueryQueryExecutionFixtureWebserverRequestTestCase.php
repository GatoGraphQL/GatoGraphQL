<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

abstract class AbstractAdminPersistedQueryQueryExecutionFixtureWebserverRequestTestCase extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

    /**
     * Admin persisted query endpoint
     */
    protected function getEndpoint(): string
    {
        return 'wp-admin/edit.php?page=gato_graphql&action=execute_query&endpoint_group=persistedQuery&persisted_query_id=65';
    }
}
