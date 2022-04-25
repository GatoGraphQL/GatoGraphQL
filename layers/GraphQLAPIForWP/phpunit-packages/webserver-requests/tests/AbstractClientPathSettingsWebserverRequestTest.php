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
         * To test their disabled state works well, first execute
         * a REST API call to disable the client, and then re-enable
         * it afterwards.
         */
        $dataName = $this->dataName();
        $data = $this->getProvidedData();
        $this->executeRESTEndpointToUpdateClientPath($dataName, $data[0]);
    }

    protected function tearDown(): void
    {
        /**
         * Re-enable the clients for the single endpoint
         * after the "disabled" test
         */
        $dataName = $this->dataName();
        $data = $this->getProvidedData();
        $this->executeRESTEndpointToUpdateClientPath($dataName, $data[1]);

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
