<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Hooks\AddDummyCustomAdminEndpointHook;
use PHPUnitForGatoGraphQL\GatoGraphQL\Integration\AbstractFixtureEndpointWebserverRequestTestCase;
use PHPUnitForGatoGraphQL\WebserverRequests\WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

class AdminEndpointSchemaQueryExecutionFixtureWebserverRequestTest extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-admin-endpoint';
    }

    protected static function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-admin-endpoint-custom';
    }

    protected static function getEndpoint(): string
    {
        return sprintf(
            'wp-admin/edit.php?page=gatographql&action=execute_query&endpoint_group=%s',
            AddDummyCustomAdminEndpointHook::ADMIN_ENDPOINT_GROUP
        );
    }
}
