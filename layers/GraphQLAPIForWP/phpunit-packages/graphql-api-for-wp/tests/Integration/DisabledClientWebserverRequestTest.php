<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

use PHPUnitForGraphQLAPI\GraphQLAPI\Constants\RESTAPIEndpoints;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\ExecuteRESTWebserverRequestTestCaseTrait;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Constants\Params;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Constants\ParamValues;
use PHPUnitForGraphQLAPI\WebserverRequests\AbstractDisabledClientWebserverRequestTestCase;
use PHPUnitForGraphQLAPI\WebserverRequests\RequestRESTAPIWordPressAuthenticatedUserWebserverRequestTestTrait;

/**
 * Test that disabling clients (GraphiQL/Voyager) works well
 */
class DisabledClientWebserverRequestTest extends AbstractDisabledClientWebserverRequestTestCase
{
    use RequestRESTAPIWordPressAuthenticatedUserWebserverRequestTestTrait;
    use ExecuteRESTWebserverRequestTestCaseTrait;
    use SingleEndpointClientWebserverRequestTestCaseTrait;

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
        $endpointURLPlaceholder = static::getWebserverHomeURL() . '/' . RESTAPIEndpoints::MODULE;
        $endpointURL = sprintf(
            $endpointURLPlaceholder,
            $this->getModuleID($dataName),
        );
        $options = static::getRESTEndpointRequestOptions();
        $options['query'][Params::STATE] = $clientEnabled ? ParamValues::ENABLED : ParamValues::DISABLED;
        $response = $client->post(
            $endpointURL,
            $options,
        );
        // Assert the REST API call is successful, or already fail the test
        $this->assertRESTCallIsSuccessful($response);
    }

    protected function getModuleID(string $dataName): string
    {
        return $this->getSingleEndpointClientModuleID($dataName);
    }
}
