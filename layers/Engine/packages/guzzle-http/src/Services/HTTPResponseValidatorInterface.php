<?php

declare(strict_types=1);

namespace PoP\GuzzleHTTP\Services;

use PoP\GuzzleHTTP\Exception\GuzzleHTTPInvalidResponseException;
use PoP\GuzzleHTTP\UpstreamWrappers\Http\Message\ResponseInterface;
use stdClass;

interface HTTPResponseValidatorInterface
{
    /**
     * @throws GuzzleHTTPInvalidResponseException
     */
    public function validateJSONResponse(
        ResponseInterface $response,
    ): void;
    /**
     * @return array<string,mixed>|stdClass
     * @throws GuzzleHTTPInvalidResponseException
     */
    public function decodeJSONResponse(
        ResponseInterface $response,
        bool $associative = false,
    ): array|stdClass;
    /**
     * @return array<string,mixed>|stdClass
     * @throws GuzzleHTTPInvalidResponseException
     */
    public function validateAndDecodeJSONResponse(
        ResponseInterface $response,
        bool $associative = false,
    ): array|stdClass;
}
