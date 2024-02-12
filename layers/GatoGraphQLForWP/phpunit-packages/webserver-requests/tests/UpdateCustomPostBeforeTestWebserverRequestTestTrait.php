<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use PHPUnitForGatoGraphQL\GatoGraphQLTesting\ExecuteRESTWebserverRequestTestCaseTrait;

/**
 * @see https://developer.wordpress.org/rest-api/reference/posts/#arguments
 */
trait UpdateCustomPostBeforeTestWebserverRequestTestTrait
{
    use ExecuteRESTWebserverRequestTestCaseTrait;

    /**
     * @param array<string,mixed> $postData
     */
    protected function executeRESTEndpointToGetOriginalCustomPostData(
        string $dataName,
    ): array {
        $client = static::getClient();
        $endpointURL = $this->getCustomPostRESTEndpointURL($dataName);
        $options = $this->getRESTEndpointRequestOptions();
        $response = $client->get(
            $endpointURL,
            $options,
        );
        // Assert the REST API call is successful, or already fail the test
        $this->assertRESTGetCallIsSuccessful($response);
        $body = $response->getBody()->__toString();
        return json_decode($body, true);
    }

    protected function getCustomPostRESTEndpointURL(string $dataName): string
    {
        return static::getWebserverHomeURL() . '/wp-json/wp/v2/' . $this->getCustomPostTypeEndpointPath() . '/' . $this->getPostID($dataName);
    }

    /**
     * @param array<string,mixed> $postData
     */
    protected function executeRESTEndpointToUpdateCustomPost(
        string $dataName,
        array $postData,
    ): void {
        $client = static::getClient();
        $endpointURL = $this->getCustomPostRESTEndpointURL($dataName);
        $options = $this->getRESTEndpointRequestOptions();
        $options[RequestOptions::QUERY] = array_merge(
            $options[RequestOptions::QUERY] ?? [],
            $postData,
        );
        $response = $client->post(
            $endpointURL,
            $options,
        );
        // Assert the REST API call is successful, or already fail the test
        $this->assertRESTPostCallIsSuccessful($response, $dataName, $endpointURL, $options);
    }

    protected function getCustomPostTypeEndpointPath(): string
    {
        return 'posts';
    }

    abstract protected static function getClient(): Client;

    abstract protected function getRESTEndpointRequestOptions(): array;

    abstract protected function getPostID(string $dataName): int;
}
