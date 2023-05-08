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
 * @see layers/GatoGraphQLForWP/phpunit-plugins/gato-graphql-testing/src/Executers/GatoGraphQLAdminEndpointsTestExecuter.php
 */
class GatoGraphQLAdminEndpointsQueryExecutionFixtureWebserverRequestTest extends AbstractEndpointWebserverRequestTestCase
{
    protected function getEndpoint(): string
    {
        return GeneralUtils::addQueryArgs(
            [
                'actions' => [
                    Actions::TEST_GATO_GRAPHQL_ADMIN_ENDPOINTS,
                ],
            ],
            'graphql/'
        );
    }

    /**
     * @return array<string,array<mixed>>
     */
    protected function provideEndpointEntries(): array
    {
        return [
            'gato-graphql-admin-endpoints' => [
                'application/json',
                $this->getGraphQLExpectedResponse(),
                $this->getEndpoint(),
                [],
                $this->getGraphQLQuery(),
            ],
        ];
    }

    protected function getGraphQLQuery(): string
    {
        return <<<GRAPHQL
            {
                id
            }
        GRAPHQL;
    }

    protected function getGraphQLExpectedResponse(): string
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
