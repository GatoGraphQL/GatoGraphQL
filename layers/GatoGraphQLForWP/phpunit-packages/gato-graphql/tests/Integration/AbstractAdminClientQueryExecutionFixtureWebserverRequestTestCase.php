<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

abstract class AbstractAdminClientQueryExecutionFixtureWebserverRequestTestCase extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

    /**
     * Admin client endpoint
     */
    protected function getEndpoint(): string
    {
        return 'wp-admin/edit.php?page=gato_graphql&action=execute_query';
    }
}
