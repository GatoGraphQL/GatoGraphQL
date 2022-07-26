<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use PHPUnitForGraphQLAPI\GraphQLAPI\Constants\RESTAPIEndpoints;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\ExecuteRESTWebserverRequestTestCaseTrait;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Constants\Params;
use PHPUnitForGraphQLAPI\GraphQLAPITesting\RESTAPI\Response\ResponseKeys;

trait ModifyCPTBlockAttributesWebserverRequestTestCaseTrait
{
    use RequestRESTAPIWordPressAuthenticatedUserWebserverRequestTestTrait;
    use ExecuteRESTWebserverRequestTestCaseTrait;

    /**
     * @var array<mixed>
     */
    protected array $previousBlockAttributesValue;

    abstract public static function assertEquals($expected, $actual, string $message = ''): void;
    abstract public static function assertNotEquals($expected, $actual, string $message = ''): void;

    protected function executeCPTBlockAttributesSetUpTearDown(string $dataName): bool
    {
        return true;
    }

    /**
     * Execute a REST API to update the client path before the test
     */
    protected function modifyCPTBlockAttributesSetUp(): void
    {
        $dataName = $this->dataName();
        if (!$this->executeCPTBlockAttributesSetUpTearDown($dataName)) {
            return;
        }

        /**
         * First obtain and cache the previous value,
         * so it can be retrieved via REST
         * (before the modifications is carried out)
         */
        $this->previousBlockAttributesValue = $this->getCPTBlockAttributesOriginalValue();
        $newValue = $this->getCPTBlockAttributesNewValue();

        // Make sure the 2 values are indeed different
        $this->assertNotEquals(
            $newValue,
            $this->previousBlockAttributesValue,
            sprintf(
                'The new value to execute the REST API call to modify the CPT block attributes is \'%s\', but this is the same as the current value, and these must be different.',
                json_encode($newValue)
            )
        );

        // Update the settings
        $this->executeRESTEndpointToUpdateCPTBlockAttributes(
            $dataName,
            $newValue,
        );
    }

    /**
     * @return array<mixed>
     */
    abstract protected function getCPTBlockAttributesNewValue(): array;

    /**
     * Execute a REST API to restore the client path after the test
     */
    protected function modifyCPTBlockAttributesTearDown(): void
    {
        $dataName = $this->dataName();
        if (!$this->executeCPTBlockAttributesSetUpTearDown($dataName)) {
            return;
        }

        $this->executeRESTEndpointToUpdateCPTBlockAttributes(
            $dataName,
            $this->previousBlockAttributesValue,
        );
    }

    /**
     * By default, execute a REST call to obtain the current
     * value from the server
     */
    protected function getCPTBlockAttributesOriginalValue(): mixed
    {
        return $this->executeRESTEndpointToGetCPTBlockAttributes(
            $this->dataName(),
        );
    }

    protected function executeRESTEndpointToGetCPTBlockAttributes(
        string $dataName,
    ): array {
        $client = static::getClient();
        $endpointURLPlaceholder = static::getWebserverHomeURL() . '/' . RESTAPIEndpoints::CPT_BLOCK_ATTRIBUTES;
        $endpointURL = sprintf(
            $endpointURLPlaceholder,
            $this->getCustomPostID($dataName),
            $this->getBlockNamespacedID($dataName),
        );
        $options = $this->getRESTEndpointRequestOptions();
        $response = $client->get(
            $endpointURL,
            $options,
        );
        // Assert the REST API call is successful, or already fail the test
        $this->assertRESTGetCallIsSuccessful($response);
        $endpointResponse = json_decode($response->getBody()->__toString(), true);
        return $endpointResponse[ResponseKeys::BLOCK_ATTRS];
    }

    protected function executeRESTEndpointToUpdateCPTBlockAttributes(
        string $dataName,
        array $value,
    ): void {
        $client = static::getClient();
        $endpointURLPlaceholder = static::getWebserverHomeURL() . '/' . RESTAPIEndpoints::CPT_BLOCK_ATTRIBUTES;
        $endpointURL = sprintf(
            $endpointURLPlaceholder,
            $this->getCustomPostID($dataName),
            $this->getBlockNamespacedID($dataName),
        );
        $options = $this->getRESTEndpointRequestOptions();
        /**
         * For some reason, passing an empty array doesn't work,
         * it doesn't append it to the "query".
         *
         * In that case, pass an empty string instead of an empty array,
         * then it works!
         */
        $options['query'][Params::BLOCK_ATTRIBUTE_VALUES] = $value === [] ? '' : $value;
        $response = $client->post(
            $endpointURL,
            $options,
        );
        // Assert the REST API call is successful, or already fail the test
        $this->assertRESTPostCallIsSuccessful($response);
    }

    abstract protected function getCustomPostID(string $dataName): int;

    abstract protected function getBlockNamespacedID(string $dataName): string;
}
