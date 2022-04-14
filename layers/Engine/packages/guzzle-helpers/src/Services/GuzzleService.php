<?php

declare(strict_types=1);

namespace PoP\GuzzleHelpers\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\Utils;
use GuzzleHttp\RequestOptions;
use PoP\GuzzleHelpers\Exception\GuzzleInvalidResponseException;
use PoP\GuzzleHelpers\Exception\GuzzleRequestException;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use Psr\Http\Message\ResponseInterface;

class GuzzleService implements GuzzleServiceInterface
{
    protected ?Client $client = null;

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    /**
     * Execute a JSON request to the passed endpoint URL and form params
     *
     * @param string $url The Endpoint URL
     * @param array<string,mixed> $bodyJSONQuery The form params
     * @return array<string,mixed> The payload if successful as an array
     * @throws GuzzleRequestException
     * @throws GuzzleInvalidResponseException
     */
    public function requestJSON(string $url, array $bodyJSONQuery = [], string $method = 'POST'): array
    {
        $client = $this->getClient();
        $options = [
            RequestOptions::JSON => $bodyJSONQuery,
        ];
        try {
            $response = $client->request($method, $url, $options);
        } catch (RequestException $exception) {
            throw new GuzzleRequestException(
                $exception->getMessage(),
                0,
                $exception
            );
        }
        return self::validateAndDecodeJSONResponse($response);
    }

    protected function getClient(): Client
    {
        if ($this->client === null) {
            $client = $this->createClient();
        }
        return $client;
    }

    protected function createClient(): Client
    {
        return new Client();
    }

    /**
     * @return array<string,mixed>
     * @throws GuzzleInvalidResponseException
     */
    protected function validateAndDecodeJSONResponse(ResponseInterface $response): array
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        if ($response->getStatusCode() !== 200) {
            throw new GuzzleInvalidResponseException(
                sprintf(
                    $translationAPI->__('The response status code is \'%s\' instead of the expected \'%s\'', 'guzzle-helpers'),
                    $response->getStatusCode(),
                    200
                )
            );
        }
        $contentType = $response->getHeaderLine('content-type');
        // It must be a JSON content type, for which it's either
        // application/json or one of its opinionated variants,
        // which all contain +json, such as
        // application/ld+json or application/geo+json
        $isJSONContentType =
            substr($contentType, 0, strlen('application/json')) === 'application/json'
            || (
                substr($contentType, 0, strlen('application/')) === 'application/'
                && str_contains($contentType, '+json')
            );
        if (!$isJSONContentType) {
            throw new GuzzleInvalidResponseException(
                sprintf(
                    $translationAPI->__('The response content type \'%s\' is unsupported', 'guzzle-helpers'),
                    $contentType
                )
            );
        }
        $bodyResponse = $response->getBody()->__toString();
        if (!$bodyResponse) {
            throw new GuzzleInvalidResponseException(
                $translationAPI->__('The body of the response is empty', 'guzzle-helpers')
            );
        }
        return json_decode($bodyResponse, true);
    }

    /**
     * Execute several JSON requests asynchronously using the same endpoint URL and different queries
     *
     * @param string $url The Endpoint URL
     * @param array<int|string,array<string,mixed>> $bodyJSONQueries The form params
     * @return array<string,mixed> The payload if successful
     * @throws GuzzleInvalidResponseException
     */
    public function requestSingleURLMultipleQueriesAsyncJSON(string $url, array $bodyJSONQueries = [], string $method = 'POST'): array
    {
        $urls = [];
        for ($i = 0; $i < count($bodyJSONQueries); $i++) {
            $urls[] = $url;
        }
        return self::requestAsyncJSON($urls, $bodyJSONQueries, $method);
    }

    /**
     * Execute several JSON requests asynchronously
     *
     * @param string[] $urls The endpoints to fetch
     * @param array<int|string,array<string,mixed>> $bodyJSONQueries the bodyJSONQuery to attach to each URL, on the same order provided in param $urls
     * @return array<string,mixed> The payload if successful
     * @throws GuzzleInvalidResponseException
     */
    public function requestAsyncJSON(array $urls, array $bodyJSONQueries = [], string $method = 'POST'): array
    {
        if (!$urls) {
            return [];
        }

        $client = $this->getClient();
        try {
            // Build the list of promises from the URLs and the body JSON queries
            $promises = [];
            foreach ($urls as $key => $url) {
                // If there is a body JSON query, attach it to the request
                $options = [];
                if ($bodyJSONQuery = $bodyJSONQueries[$key] ?? null) {
                    $options[RequestOptions::JSON] = $bodyJSONQuery;
                }
                $promises[$key] = $client->requestAsync($method, $url, $options);
            }

            // Wait on all of the requests to complete. Throws a ConnectException
            // if any of the requests fail
            $results = Utils::unwrap($promises);

            // Wait for the requests to complete, even if some of them fail
            $results = Utils::settle($promises)->wait();
        } catch (RequestException $exception) {
            throw new GuzzleRequestException(
                $exception->getMessage(),
                0,
                $exception
            );
        }

        // You can access each result using the key provided to the unwrap function.
        return array_map(
            function ($result) {
                return self::validateAndDecodeJSONResponse($result['value']);
            },
            $results
        );
    }
}
