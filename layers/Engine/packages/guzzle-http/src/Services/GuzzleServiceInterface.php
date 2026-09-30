<?php

declare(strict_types=1);

namespace PoP\GuzzleHTTP\Services;

use Exception;
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

    public function getClient(): Client;

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
     * Execute several JSON requests asynchronously, and give back what each
     * of them produced: its response, or the exception it failed with, so
     * that one request failing does not throw away what the requests beside
     * it brought back.
     *
     * @param RequestInput[] $requestInputs
     * @return array<ResponseInterface|Exception>
     *
     * @throws GuzzleHTTPRequestException
     */
    public function sendAsyncHTTPRequestSettled(array $requestInputs): array;

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
