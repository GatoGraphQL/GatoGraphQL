<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Hooks\AddDummyCustomAdminEndpointHook;
use PHPUnitForGatoGraphQL\GatoGraphQL\Integration\AbstractFixtureEndpointWebserverRequestTestCase;
use PHPUnitForGatoGraphQL\WebserverRequests\WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

class AdminCustomEndpointSchemaQueryExecutionFixtureWebserverRequestTest extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

    protected function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-admin-custom-endpoint';
    }

    protected function getEndpoint(): string
    {
        return sprintf(
            'wp-admin/edit.php?page=gato_graphql&action=execute_query&endpoint_group=%s',
            AddDummyCustomAdminEndpointHook::ADMIN_ENDPOINT_GROUP
        );
    }
}
