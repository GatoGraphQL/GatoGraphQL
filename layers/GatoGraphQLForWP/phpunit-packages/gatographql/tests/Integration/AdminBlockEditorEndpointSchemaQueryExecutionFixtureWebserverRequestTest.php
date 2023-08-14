<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GatoGraphQL\GatoGraphQL\Constants\AdminGraphQLEndpointGroups;
use PHPUnitForGatoGraphQL\GatoGraphQL\Integration\AbstractFixtureEndpointWebserverRequestTestCase;
use PHPUnitForGatoGraphQL\WebserverRequests\WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

class AdminBlockEditorEndpointSchemaQueryExecutionFixtureWebserverRequestTest extends AbstractFixtureEndpointWebserverRequestTestCase
{
    use WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

    protected static function getFixtureFolder(): string
    {
        return __DIR__ . '/fixture-admin-endpoint';
    }

    protected static function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-admin-endpoint-block-editor';
    }

    protected static function getEndpoint(): string
    {
        return sprintf(
            'wp-admin/edit.php?page=gatographql&action=execute_query&endpoint_group=%s',
            AdminGraphQLEndpointGroups::BLOCK_EDITOR
        );
    }
}
