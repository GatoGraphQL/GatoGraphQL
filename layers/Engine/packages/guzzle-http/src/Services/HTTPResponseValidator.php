<?php

declare(strict_types=1);

namespace PoP\GuzzleHTTP\Services;

use PoP\GuzzleHTTP\Exception\GuzzleHTTPInvalidResponseException;
use PoP\GuzzleHTTP\UpstreamWrappers\Http\Message\ResponseInterface;
use PoP\Root\Services\AbstractBasicService;
use stdClass;

class HTTPResponseValidator extends AbstractBasicService implements HTTPResponseValidatorInterface
{
    /**
     * @throws GuzzleHTTPInvalidResponseException
     */
    public function validateJSONResponse(
        ResponseInterface $response,
    ): void {
        /**
         * Validate the response was successful, i.e. if its
         * status code is in the 200s range, but ignore codes
         * 204 upwards as they will have no (JSON) content.
         *
         * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Status#successful_responses
         */
        $statusCode = $response->getStatusCode();
        if (!($statusCode >= 200 && $statusCode <= 203)) {
            $bodyResponse = $response->getBody()->__toString();
            $errorMessage = !empty($bodyResponse)
                ? sprintf(
                    $this->__('Response with status code \'%s\': %s', 'guzzle-http'),
                    $statusCode,
                    $bodyResponse,
                )
                : sprintf(
                    $this->__('Response has status code \'%s\'', 'guzzle-http'),
                    $statusCode,
                );
            throw new GuzzleHTTPInvalidResponseException($errorMessage);
        }
    }

    /**
     * @return array<string,mixed>|stdClass
     * @throws GuzzleHTTPInvalidResponseException
     */
    public function decodeJSONResponse(
        ResponseInterface $response,
        bool $associative = false,
    ): array|stdClass {
        /**
         * It must be a JSON content type, for which it's either
         * `application/json` or one of its opinionated variants,
         * which all contain `+json`, such as
         * `application/ld+json` or `application/geo+json`
         */
        $contentType = $response->getHeaderLine('content-type');
        $isJSONContentType =
            substr($contentType, 0, strlen('application/json')) === 'application/json'
            || (
                substr($contentType, 0, strlen('application/')) === 'application/'
                && str_contains($contentType, '+json')
            );
        if (!$isJSONContentType) {
            throw new GuzzleHTTPInvalidResponseException(
                sprintf(
                    $this->__('The response content type is \'%s\', but \'application/json\' (or one of its JSON variants) is expected', 'guzzle-http'),
                    $contentType
                )
            );
        }

        $bodyResponse = $response->getBody()->__toString();
        if (!$bodyResponse) {
            throw new GuzzleHTTPInvalidResponseException(
                $this->__('The body of the response is empty', 'guzzle-http')
            );
        }

        $decodedJSON = json_decode(
            $bodyResponse,
            $associative
        );
        if (!is_array($decodedJSON) && !($decodedJSON instanceof stdClass)) {
            throw new GuzzleHTTPInvalidResponseException(
                sprintf(
                    $this->__('The body of the response could not be JSON-decoded: \'%s\'', 'guzzle-http'),
                    $bodyResponse
                )
            );
        }

        return $decodedJSON;
    }

    /**
     * @return array<string,mixed>|stdClass
     * @throws GuzzleHTTPInvalidResponseException
     */
    public function validateAndDecodeJSONResponse(
        ResponseInterface $response,
        bool $associative = false,
    ): array|stdClass {
        $this->validateJSONResponse($response);
        return $this->decodeJSONResponse($response, $associative);
    }
}
