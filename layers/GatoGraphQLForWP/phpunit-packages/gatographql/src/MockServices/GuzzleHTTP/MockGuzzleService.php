<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\MockServices\GuzzleHTTP;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
// use PoP\GuzzleHTTP\Services\GuzzleService;
use GuzzleHttp\Handler\MockHandler;
use PoP\GuzzleHTTP\Exception\GuzzleHTTPRequestException;
use PoP\GuzzleHTTP\ObjectModels\RequestInput;
use PoP\GuzzleHTTP\Services\GuzzleServiceInterface;
use PoP\GuzzleHTTP\UpstreamWrappers\Http\Message\ResponseInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface as UpstreamResponseInterface;
use Throwable;

/**
 * Mock the response from the Guzzle client.
 * The mock responses must be injected in the
 * PHPUnit test, by doing:
 *
 * ```
 * $this->getMockGuzzleService()->getMock()->append(new Response(201));
 * ```
 *
 * @see https://docs.guzzlephp.org/en/stable/testing.html
 */
class MockGuzzleService implements MockGuzzleServiceInterface
{
    protected ?MockHandler $mockHandler = null;

    public function __construct(protected GuzzleServiceInterface $guzzleService)
    {
        $this->guzzleService->setClient($this->createClient());
    }

    public function setClient(Client $client): void
    {
        $this->guzzleService->setClient($client);
    }

    /**
     * Execute an HTTP request to the passed endpoint URL and form params
     *
     * @throws GuzzleHTTPRequestException
     */
    public function sendHTTPRequest(RequestInput $requestInput): ResponseInterface
    {
        return $this->guzzleService->sendHTTPRequest($requestInput);
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
        return $this->guzzleService->sendAsyncHTTPRequest($requestInputs);
    }

    protected function createClient(): Client
    {
        return new Client(
            [
                'handler' => HandlerStack::create($this->getMockHandler()),
            ]
        );
    }

    public function getMockHandler(): MockHandler
    {
        if ($this->mockHandler === null) {
            $this->mockHandler = new MockHandler();
        }
        return $this->mockHandler;
    }

    /**
     * @param mixed[] $handlerContext
     */
    public function createRequestException(
        RequestInterface $request,
        ?UpstreamResponseInterface $response = null,
        ?Throwable $previous = null,
        array $handlerContext = [],
    ): RequestException {
        return $this->guzzleService->createRequestException(
            $request,
            $response,
            $previous,
            $handlerContext,
        );
    }

    public function createRequest(RequestInput $requestInput): RequestInterface
    {
        return $this->guzzleService->createRequest($requestInput);
    }
}
