<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Constants\ParamValues;
use PHPUnitForGraphQLAPI\WebserverRequests\AbstractDisabledClientWebserverRequestTestCase;
use PHPUnitForGraphQLAPI\WebserverRequests\RequestRESTAPIWordPressAuthenticatedUserWebserverRequestTestTrait;
use PoP\Root\Exception\ShouldNotHappenException;

/**
 * Test that disabling clients (GraphiQL/Voyager) works well
 */
class DisabledClientWebserverRequestTest extends AbstractDisabledClientWebserverRequestTestCase
{
    use RequestRESTAPIWordPressAuthenticatedUserWebserverRequestTestTrait;

    protected function setUp(): void
    {
        parent::setUp();

        /**
         * To test their disabled state works well, first execute
         * a REST API call to disable the client, and then re-enable
         * it afterwards.
         */
        $dataName = $this->dataName();
        if (str_starts_with($dataName, 'single-endpoint-')) {
            $this->executeRESTEndpointToEnableOrDisableClient($dataName, false);
        }
    }

    protected function tearDown(): void
    {
        /**
         * Re-enable the clients for the single endpoint
         * after the "disabled" test
         */
        $dataName = $this->dataName();
        if (str_starts_with($dataName, 'single-endpoint-')) {
            $this->executeRESTEndpointToEnableOrDisableClient($dataName, true);
        }

        parent::tearDown();
    }

    /**
     * @return array<string,mixed[]>
     */
    protected function provideDisabledClientEntries(): array
    {
        return [
            'single-endpoint-graphiql' => [
                'graphiql/',
                404,
            ],
            'single-endpoint-voyager' => [
                'schema/',
                404,
            ],
            'custom-endpoint-graphiql' => [
                'graphql/customers/penguin-books/?view=graphiql',
                200,
            ],
            'custom-endpoint-voyager' => [
                'graphql/customers/penguin-books/?view=schema',
                200,
            ],
        ];
    }

    protected function executeRESTEndpointToEnableOrDisableClient(
        string $dataName,
        bool $clientEnabled
    ): void {
        $client = static::getClient();
        $restEndpointPlaceholder = 'wp-json/graphql-api/v1/admin/modules/%s/?state=%s';
        $endpointURLPlaceholder = static::getWebserverHomeURL() . '/' . $restEndpointPlaceholder;
        $endpointURL = sprintf(
            $endpointURLPlaceholder,
            $this->getModuleID($dataName),
            $clientEnabled ? ParamValues::ENABLED : ParamValues::DISABLED
        );
        $response = $client->post(
            $endpointURL,
            static::getRESTEndpointRequestOptions()
        );
    }

    /**
     * To visualize the list of all the modules, and find the "moduleID":
     *
     * @see http://graphql-api.lndo.site/wp-json/graphql-api/v1/admin/modules
     */
    protected function getModuleID(string $dataName): string
    {
        return match ($dataName) {
            'single-endpoint-graphiql' => 'graphqlapi_graphqlapi_graphiql-for-single-endpoint',
            'single-endpoint-voyager' => 'graphqlapi_graphqlapi_interactive-schema-for-single-endpoint',
            default => throw new ShouldNotHappenException(
                sprintf(
                    'There is no moduleID configured for $dataName \'%s\'',
                    $dataName
                )
            )
        };
    }
}
