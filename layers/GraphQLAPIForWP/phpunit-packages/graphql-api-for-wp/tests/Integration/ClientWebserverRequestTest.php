<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

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
     * Disable the clients for the single endpoint
     * before the "disabled" test
     */
    protected function beforeRunningTest(
        string $dataName,
        string $clientEndpoint,
        bool $enabled,
    ): void {
        if (!$enabled && str_starts_with($dataName, 'single-endpoint-')) {
            $this->executeRESTEndpointToEnableOrDisableClient($dataName, $clientEndpoint, false);
        }
        parent::beforeRunningTest(
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
        $restEndpointPlaceholder = 'wp-json/graphql-api/v1/admin/settings/?name=%s&value=%s';
        $endpointURLPlaceholder = static::getWebserverHomeURL() . '/' . $restEndpointPlaceholder;
        $settingsNames = [
            'single-endpoint-graphiql' => 'graphiql-client-isEnabled',
            'single-endpoint-voyager' => 'voyager-client-isEnabled',
        ];
        $endpointURL = sprintf(
            $endpointURLPlaceholder,
            $settingsNames[$dataName],
            $clientEnabled ? '1' : '0'
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
    protected function afterRunningTest(
        string $dataName,
        string $clientEndpoint,
        bool $enabled,
    ): void {
        if (!$enabled && str_starts_with($dataName, 'single-endpoint-')) {
            $this->executeRESTEndpointToEnableOrDisableClient($dataName, $clientEndpoint, true);
        }
        parent::afterRunningTest(
            $dataName,
            $clientEndpoint,
            $enabled,
        );
    }
}
