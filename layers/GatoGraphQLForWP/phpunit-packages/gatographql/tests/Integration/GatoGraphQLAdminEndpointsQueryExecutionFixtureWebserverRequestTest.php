<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants\Actions;
use PHPUnitForGatoGraphQL\WebserverRequests\AbstractEndpointWebserverRequestTestCase;
use PoP\ComponentModel\Misc\GeneralUtils;

/**
 * Test the Admin Endpoints provided by class `GatoGraphQL`
 * are the expected ones.
 *
 * @see layers/GatoGraphQLForWP/phpunit-plugins/gatographql-testing/src/Executers/GatoGraphQLAdminEndpointsTestExecuter.php
 */
class GatoGraphQLAdminEndpointsQueryExecutionFixtureWebserverRequestTest extends AbstractEndpointWebserverRequestTestCase
{
    protected static function getEndpoint(): string
    {
        return GeneralUtils::addQueryArgs(
            [
                'actions' => [
                    Actions::TEST_GATOGRAPHQL_ADMIN_ENDPOINTS,
                ],
            ],
            'graphql/'
        );
    }

    /**
     * @return array<string,array<mixed>>
     */
    public static function provideEndpointEntries(): array
    {
        return [
            'gatographql-admin-endpoints' => [
                'application/json',
                static::getGraphQLExpectedResponse(),
                static::getEndpoint(),
                [],
                static::getGraphQLQuery(),
            ],
        ];
    }

    protected static function getGraphQLQuery(): string
    {
        return <<<GRAPHQL
            {
                id
            }
        GRAPHQL;
    }

    protected static function getGraphQLExpectedResponse(): string
    {
        return <<<JSON
        {
            "data": {
                "id": "root"
            }
        }
        JSON;
    }
}
