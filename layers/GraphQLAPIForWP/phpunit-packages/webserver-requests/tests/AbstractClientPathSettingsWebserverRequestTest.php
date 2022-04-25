<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use GraphQLAPI\GraphQLAPI\Constants\ModuleSettingOptions;
use PHPUnitForGraphQLAPI\GraphQLAPI\Constants\RESTAPIEndpoints;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\ExecuteRESTWebserverRequestTestCaseTrait;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Constants\Params;

/**
 * Test that enabling/disabling clients (GraphiQL/Voyager)
 * in Custom Endpoints works well
 */
abstract class AbstractClientPathSettingsWebserverRequestTest extends AbstractWebserverRequestTestCase
{
    use RequestRESTAPIWordPressAuthenticatedUserWebserverRequestTestTrait;
    use ExecuteRESTWebserverRequestTestCaseTrait;
    use ClientWebserverRequestTestCaseTrait;

    /**
     * Test that:
     *
     * 1. The client under the new path returns a 200
     * 2. The client under the old path returns a 404
     *
     * @dataProvider provideClientPathEntries
     */
    public function testClientPathsUpdated(
        string $newClientPath,
        string $previousClientPath,
    ): void {
        $this->testEnabledOrDisabledClients($newClientPath, 200, true);
        $this->testEnabledOrDisabledClients($previousClientPath, 404, false);
    }

    /**
     * @return array<string,string[]> Array of 2 elements: [ ${newClientPath}, ${previousClientPath} ]
     */
    abstract protected function provideClientPathEntries(): array;

    protected function setUp(): void
    {
        parent::setUp();

        /**
         * Execute a REST API to update the client path before the test
         */
        $dataName = $this->dataName();
        $data = $this->getProvidedData();
        $newClientPath = $data[0];
        $this->executeRESTEndpointToUpdateClientPath($dataName, $newClientPath);
    }

    protected function tearDown(): void
    {
        /**
         * Execute a REST API to restore the client path after the test
         */
        $dataName = $this->dataName();
        $data = $this->getProvidedData();
        $previousClientPath = $data[1];
        $this->executeRESTEndpointToUpdateClientPath($dataName, $previousClientPath);

        parent::tearDown();
    }

    protected function executeRESTEndpointToUpdateClientPath(
        string $dataName,
        string $path
    ): void {
        $client = static::getClient();
        $endpointURLPlaceholder = static::getWebserverHomeURL() . '/' . RESTAPIEndpoints::MODULE_SETTINGS;
        $endpointURL = sprintf(
            $endpointURLPlaceholder,
            $this->getModuleID($dataName),
        );
        $options = static::getRESTEndpointRequestOptions();
        $options['query'][Params::OPTION_VALUES] = [
            ModuleSettingOptions::PATH => $path,
        ];
        $response = $client->post(
            $endpointURL,
            $options,
        );
        // Assert the REST API call is successful, or already fail the test
        $this->assertRESTCallIsSuccessful($response);
    }

    /**
     * To visualize the list of all the modules, and find the "moduleID":
     *
     * @see http://graphql-api.lndo.site/wp-json/graphql-api/v1/admin/modules
     */
    abstract protected function getModuleID(string $dataName): string;
}
