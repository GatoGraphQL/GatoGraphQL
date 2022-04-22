<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Controllers\ModulesAdminRESTController;
use PHPUnitForGraphQLAPI\WebserverRequests\AbstractClientWebserverRequestTestCase;
use PHPUnitForGraphQLAPI\WebserverRequests\RequestRESTAPIWordPressAuthenticatedUserWebserverRequestTestTrait;

/**
 * Test that enabling/disabling clients (GraphiQL/Voyager)
 * in Custom Endpoints works well
 */
class ClientWebserverRequestTest extends AbstractClientWebserverRequestTestCase
{
    use RequestRESTAPIWordPressAuthenticatedUserWebserverRequestTestTrait;
    
    /**
     * @return array<string,string[]>
     */
    protected function provideEnabledClientEntries(): array
    {
        return [
            'single-endpoint-graphiql' => [
                'graphiql/',
            ],
            'single-endpoint-voyager' => [
                'schema/',
            ],
            'custom-endpoint-graphiql' => [
                'graphql/mobile-app/?view=graphiql',
            ],
            'custom-endpoint-voyager' => [
                'graphql/mobile-app/?view=schema',
            ],
        ];
    }

    /**
     * @return array<string,string[]>
     */
    protected function provideDisabledClientEntries(): array
    {
        return [
            'single-endpoint-graphiql' => [
                'graphiql/',
            ],
            'single-endpoint-voyager' => [
                'schema/',
            ],
            'custom-endpoint-graphiql' => [
                'graphql/customers/penguin-books/?view=graphiql',
            ],
            'custom-endpoint-voyager' => [
                'graphql/customers/penguin-books/?view=schema',
            ],
        ];
    }

    /**
     * The single endpoint clients (GraphiQL and Voyager)
     * are by default enabled.
     *
     * To test their disabled state works well, first execute
     * a REST API call to disable the client, and then re-enable
     * it afterwards.
     */
    protected function beforeFixtureClientRequest(
        string $dataName,
        string $clientEndpoint,
        bool $enabled,
    ): void {
        if (!$enabled && str_starts_with($dataName, 'single-endpoint-')) {
            $this->executeRESTEndpointToEnableOrDisableClient($dataName, $clientEndpoint, false);
        }
        parent::beforeFixtureClientRequest(
            $dataName,
            $clientEndpoint,
            $enabled,
        );
    }

    protected function executeRESTEndpointToEnableOrDisableClient(
        string $dataName,
        string $clientEndpoint,
        bool $clientEnabled
    ): void {
        $client = static::getClient();
        $restEndpointPlaceholder = 'wp-json/graphql-api/v1/admin/modules/%s/?state=%s';
        $endpointURLPlaceholder = static::getWebserverHomeURL() . '/' . $restEndpointPlaceholder;
        $moduleIDs = [
            'single-endpoint-graphiql' => 'graphqlapi_graphqlapi_graphiql-for-single-endpoint',
            'single-endpoint-voyager' => 'graphqlapi_graphqlapi_interactive-schema-for-single-endpoint',
        ];
        $endpointURL = sprintf(
            $endpointURLPlaceholder,
            $moduleIDs[$dataName],
            $clientEnabled ? ModulesAdminRESTController::MODULE_STATE_ENABLED : ModulesAdminRESTController::MODULE_STATE_DISABLED
        );
        $response = $client->post(
            $endpointURL,
            static::getRESTEndpointRequestOptions()
        );
    }

    /**
     * Re-enable the clients for the single endpoint
     * after the "disabled" test
     */
    protected function afterFixtureClientRequest(
        string $dataName,
        string $clientEndpoint,
        bool $enabled,
    ): void {
        if (!$enabled && str_starts_with($dataName, 'single-endpoint-')) {
            $this->executeRESTEndpointToEnableOrDisableClient($dataName, $clientEndpoint, true);
        }
        parent::afterFixtureClientRequest(
            $dataName,
            $clientEndpoint,
            $enabled,
        );
    }
}
