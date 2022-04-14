<?php

declare(strict_types=1);

namespace PoP\GuzzleHelpers\Services;

use PoP\GuzzleHelpers\Exception\GuzzleInvalidResponseException;
use PoP\GuzzleHelpers\Exception\GuzzleRequestException;

interface GuzzleServiceInterface
{
    /**
     * Execute a JSON request to the passed endpoint URL and form params
     *
     * @param string $url The Endpoint URL
     * @param array<string,mixed> $bodyJSONQuery The form params
     * @return array<string,mixed> The payload if successful as an array
     * @throws GuzzleRequestException
     * @throws GuzzleInvalidResponseException
     */
    public function requestJSON(string $url, array $bodyJSONQuery = [], string $method = 'POST'): array;

    /**
     * Execute several JSON requests asynchronously using the same endpoint URL and different queries
     *
     * @param string $url The Endpoint URL
     * @param array<int|string,array<string,mixed>> $bodyJSONQueries The form params
     * @return array<string,mixed> The payload if successful
     * @throws GuzzleInvalidResponseException
     */
    public function requestSingleURLMultipleQueriesAsyncJSON(string $url, array $bodyJSONQueries = [], string $method = 'POST'): array;

    /**
     * Execute several JSON requests asynchronously
     *
     * @param string[] $urls The endpoints to fetch
     * @param array<int|string,array<string,mixed>> $bodyJSONQueries the bodyJSONQuery to attach to each URL, on the same order provided in param $urls
     * @return array<string,mixed> The payload if successful
     * @throws GuzzleInvalidResponseException
     */
    public function requestAsyncJSON(array $urls, array $bodyJSONQueries = [], string $method = 'POST'): array;
}
