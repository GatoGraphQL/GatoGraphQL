<?php

declare(strict_types=1);

namespace PoP\GuzzleHelpers;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use PoP\ComponentModel\ErrorHandling\Error;
use GuzzleHttp\RequestOptions;

use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use PoP\Translation\Facades\TranslationAPIFacade;

class GuzzleHelpers
{
    /**
     * Execute a JSON request to the passed endpoint URL and form params
     *
     * @param string $url The Endpoint URL
     * @param array $bodyJSONQuery The form params
     * @param string $method
     * @return array|Error The payload if successful as an array, or an Error object containing the error message in case of failure
     */
    public static function requestJSON(string $url, array $bodyJSONQuery = [], string $method = 'POST')
    {
        $client = new Client();
        try {
            $options = [
                RequestOptions::JSON => $bodyJSONQuery,
            ];
            $response = $client->request($method, $url, $options);
            return self::validateAndDecodeJSONResponse($response);
        } catch (RequestException $exception) {
            return new Error(
                'request-failed',
                $exception->getMessage()
            );
        }
        return [];
    }

    protected static function validateAndDecodeJSONResponse(ResponseInterface $response)
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        if ($response->getStatusCode() != 200) {
            // Throw an error
            return new Error(
                'request-failed',
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
            substr($contentType, 0, strlen('application/json')) == 'application/json'
            || (
                substr($contentType, 0, strlen('application/')) == 'application/'
                && strpos($contentType, '+json') !== false
            );
        if (!$isJSONContentType) {
            // Throw an error
            return new Error(
                'request-failed',
                sprintf(
                    $translationAPI->__('The response content type \'%s\' is unsupported', 'guzzle-helpers'),
                    $contentType
                )
            );
        }
        $body = $response->getBody();
        if (!$body) {
            // Throw an error
            return new Error(
                'request-failed',
                $translationAPI->__('The body of the response is empty', 'guzzle-helpers')
            );
        }
        return json_decode($body->__toString(), true);
    }

    /**
     * Execute several JSON requests asynchronously using the same endpoint URL and different queries
     *
     * @param string $url The Endpoint URL
     * @param array $bodyJSONQueries The form params
     * @param string $method
     * @return mixed The payload if successful as an array, or an Error object containing the error message in case of failure
     */
    public static function requestSingleURLMultipleQueriesAsyncJSON(string $url, array $bodyJSONQueries = [], string $method = 'POST')
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
     * @param array $urls The endpoints to fetch
     * @param array $bodyJSONQueries the bodyJSONQuery to attach to each URL, on the same order provided in param $urls
     * @param string $method
     * @return void
     */
    public static function requestAsyncJSON(array $urls, array $bodyJSONQueries = [], string $method = 'POST')
    {
        if (!$urls) {
            return [];
        }

        $client = new Client();
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
            $results = Promise\unwrap($promises);

            // Wait for the requests to complete, even if some of them fail
            $results = Promise\settle($promises)->wait();

            // You can access each result using the key provided to the unwrap function.
            return array_map(
                function ($result) {
                    return self::validateAndDecodeJSONResponse($result['value']);
                },
                $results
            );
        } catch (RequestException $exception) {
            return [
                new Error(
                    'request-failed',
                    $exception->getMessage()
                )
            ];
        }
        return [];
    }
}
