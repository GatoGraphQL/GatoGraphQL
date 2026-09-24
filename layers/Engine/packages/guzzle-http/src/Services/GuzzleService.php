<?php

declare(strict_types=1);

namespace PoP\GuzzleHTTP\Services;

use Exception;
use GuzzleHttp\BodySummarizer;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Promise\Utils;
use GuzzleHttp\Psr7\Request;
use PoP\ComponentModel\App;
use PoP\Root\Services\AbstractBasicService;
use PoP\GuzzleHTTP\Exception\GuzzleHTTPRequestException;
use PoP\GuzzleHTTP\Module;
use PoP\GuzzleHTTP\ModuleConfiguration;
use PoP\GuzzleHTTP\ObjectModels\RequestInput;
use PoP\GuzzleHTTP\UpstreamWrappers\Http\Message\ResponseInterface;
use PoP\GuzzleHTTP\UpstreamWrappers\Http\Message\ResponseWrapper;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface as UpstreamResponseInterface;
use Throwable;

class GuzzleService extends AbstractBasicService implements GuzzleServiceInterface
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

    public function getClient(): Client
    {
        if ($this->client === null) {
            $this->client = $this->createClient();
        }
        return $this->client;
    }

    protected function createClient(): Client
    {
        return new Client($this->getClientConfig());
    }

    /**
     * @return array<string,mixed>
     */
    protected function getClientConfig(): array
    {
        return [
            'headers' => $this->getClientConfigHeaders(),
        ];
    }

    /**
     * @return array<string,mixed>
     */
    protected function getClientConfigHeaders(): array
    {
        $headers = [];
        $referer = $this->getClientConfigReferer();
        if ($referer !== null) {
            $headers['Referer'] = $referer;
        }
        return $headers;
    }

    protected function getClientConfigReferer(): ?string
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getGuzzleRequestReferer();
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
        $results = $this->sendAsyncHTTPRequestSettled($requestInputs);

        // A single failure fails for all
        foreach ($results as $result) {
            if ($result instanceof Exception) {
                $this->throwException($result);
            }
        }

        /** @var ResponseInterface[] */
        return $results;
    }

    /**
     * Execute several JSON requests asynchronously, and give back what each
     * of them produced: its response, or the exception it failed with.
     *
     * A request that gets no answer at all - one that timed out, one whose
     * host refused the connection - fails as an exception and not as a
     * response, and there is nothing in a `http_errors` setting to change
     * that. Waiting on the promises together would then make that one
     * failure the answer for all of them, throwing away what the requests
     * beside it did bring back; settling them keeps each one's outcome its
     * own, and leaves it to the caller to say whether one failure fails
     * everything.
     *
     * @param RequestInput[] $requestInputs
     * @return array<ResponseInterface|Exception>
     *
     * @throws GuzzleHTTPRequestException
     */
    public function sendAsyncHTTPRequestSettled(array $requestInputs): array
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

            // Wait for the requests to complete, even if some of them fail
            $results = Utils::settle($promises)->wait();
        } catch (Exception $exception) {
            $this->throwException($exception);
        }

        $responses = [];
        /** @var array<array{state:string,value?:UpstreamResponseInterface,reason?:mixed}> $results */
        foreach ($results as $result) {
            if (($result['state'] ?? '') === PromiseInterface::FULFILLED) {
                $responses[] = new ResponseWrapper($result['value']);
                continue;
            }
            /** @var mixed */
            $reason = $result['reason'] ?? null;
            $responses[] = $reason instanceof Exception
                ? $this->createGuzzleHTTPRequestException($reason)
                : new GuzzleHTTPRequestException('The request failed');
        }
        return $responses;
    }

    /**
     * Try to increase the limit of the truncated response,
     * which is 120 chars by default.
     *
     * @see https://github.com/laravel/framework/discussions/47773
     */
    protected function maybeReplaceException(Exception $exception): Exception
    {
        if (!($exception instanceof RequestException)) {
            return $exception;
        }

        return $this->createRequestException(
            $exception->getRequest(),
            $exception->getResponse(),
            $exception->getPrevious(),
            $exception->getHandlerContext(),
        );
    }

    /**
     * @throws GuzzleHTTPRequestException
     */
    protected function throwException(Exception $exception): void
    {
        throw $this->createGuzzleHTTPRequestException($exception);
    }

    protected function createGuzzleHTTPRequestException(Exception $exception): GuzzleHTTPRequestException
    {
        $exception = $this->maybeReplaceException($exception);
        return new GuzzleHTTPRequestException(
            $exception->getMessage(),
            0,
            $exception
        );
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
        return RequestException::create(
            $request,
            ($response instanceof ResponseInterface) ? $response->getUpstreamResponse() : $response,
            $previous,
            $handlerContext,
            new BodySummarizer(1200)
        );
    }

    public function createRequest(RequestInput $requestInput): RequestInterface
    {
        return new Request($requestInput->method, $requestInput->url);
    }
}
