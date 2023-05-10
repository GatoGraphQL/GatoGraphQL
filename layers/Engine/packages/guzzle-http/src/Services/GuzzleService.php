<?php

declare(strict_types=1);

namespace PoP\GuzzleHTTP\Services;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;
use PoP\GuzzleHTTP\Exception\GuzzleHTTPRequestException;
use PoP\GuzzleHTTP\ObjectModels\RequestInput;
use PoP\GuzzleHTTP\UpstreamWrappers\Http\Message\ResponseInterface;
use PoP\GuzzleHTTP\UpstreamWrappers\Http\Message\ResponseWrapper;

class GuzzleService implements GuzzleServiceInterface
{
    protected ?Client $client = null;

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    /**
     * Execute an HTTP request to the passed endpoint URL and form params
     *
     * @throws GuzzleHTTPRequestException
     */
    public function sendHTTPRequest(RequestInput $requestInput): ResponseInterface
    {
        $client = $this->getClient();
        try {
            $response = $client->request($requestInput->method, $requestInput->url, $requestInput->options);
        } catch (Exception $exception) {
            $this->throwException($exception);
        }
        return new ResponseWrapper($response);
    }

    protected function getClient(): Client
    {
        if ($this->client === null) {
            $this->client = $this->createClient();
        }
        return $this->client;
    }

    protected function createClient(): Client
    {
        return new Client();
    }

    /**
     * Execute several JSON requests asynchronously
     *
     * @param RequestInput[] $requestInputs
     * @return ResponseInterface[]
     *
     * @throws GuzzleHTTPRequestException
     */
    public function sendAsyncHTTPRequest(array $requestInputs): array
    {
        $client = $this->getClient();
        try {
            // Build the list of promises from the URLs and the body JSON queries
            $promises = [];
            foreach ($requestInputs as $requestInput) {
                $promises[] = $client->requestAsync(
                    $requestInput->method,
                    $requestInput->url,
                    $requestInput->options,
                );
            }

            // Wait on all of the requests to complete. Throws a ConnectException
            // if any of the requests fail
            $results = Utils::unwrap($promises);

            // Wait for the requests to complete, even if some of them fail
            $results = Utils::settle($promises)->wait();
        } catch (Exception $exception) {
            $this->throwException($exception);
        }

        // You can access each result using the key provided to the unwrap function.
        return array_map(
            fn (array $result) => new ResponseWrapper($result['value']),
            $results
        );
    }

    /**
     * @throws GuzzleHTTPRequestException
     */
    protected function throwException(Exception $exception): void
    {
        throw new GuzzleHTTPRequestException(
            $exception->getMessage(),
            0,
            $exception
        );
    }
}
