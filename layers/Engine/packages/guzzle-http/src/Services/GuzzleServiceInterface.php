<?php

declare(strict_types=1);

namespace PoP\GuzzleHTTP\Services;

use GuzzleHttp\Client;
use PoP\GuzzleHTTP\Exception\GuzzleHTTPRequestException;
use PoP\GuzzleHTTP\ObjectModels\RequestInput;
use PoP\GuzzleHTTP\UpstreamWrappers\Http\Message\ResponseInterface;

interface GuzzleServiceInterface
{
    public function setClient(Client $client): void;

    /**
     * Execute an HTTP request to the passed endpoint URL and form params
     *
     * @throws GuzzleHTTPRequestException
     */
    public function sendHTTPRequest(RequestInput $requestInput): ResponseInterface;

    /**
     * Execute several JSON requests asynchronously
     *
     * @param RequestInput[] $requestInputs
     * @return ResponseInterface[]
     *
     * @throws GuzzleHTTPRequestException
     */
    public function sendAsyncHTTPRequest(array $requestInputs): array;
}
