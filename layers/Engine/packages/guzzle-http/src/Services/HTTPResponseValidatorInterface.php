<?php

declare(strict_types=1);

namespace PoP\GuzzleHTTP\Services;

use PoP\GuzzleHTTP\Exception\GuzzleHTTPInvalidResponseException;
use PoP\GuzzleHTTP\UpstreamWrappers\Http\Message\ResponseInterface;
use stdClass;

interface HTTPResponseValidatorInterface
{
    /**
     * @return array<string,mixed>|stdClass
     * @throws GuzzleHTTPInvalidResponseException
     */
    public function validateAndDecodeJSONResponse(
        ResponseInterface $response,
        bool $associative = false,
    ): array|stdClass;
}
