<?php

declare(strict_types=1);

namespace PoP\GuzzleHTTP\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use PoP\GuzzleHTTP\Exception\GuzzleHTTPRequestException;
use PoP\GuzzleHTTP\ObjectModels\RequestInput;
use PoP\GuzzleHTTP\UpstreamWrappers\Http\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface as UpstreamResponseInterface;
use Throwable;

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

    /**
     * @param mixed[] $handlerContext
     */
    public function createRequestException(
        RequestInterface $request,
        ?UpstreamResponseInterface $response = null,
        ?Throwable $previous = null,
        array $handlerContext = [],
    ): RequestException;

    public function createRequest(RequestInput $requestInput): RequestInterface;
}
