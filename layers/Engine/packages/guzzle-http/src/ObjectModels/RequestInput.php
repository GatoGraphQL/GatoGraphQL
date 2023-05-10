<?php

declare(strict_types=1);

namespace PoP\GuzzleHTTP\ObjectModels;

class RequestInput
{
    /**
     * @param array<string,mixed> $options Request options. Same input as for Guzzle: https://docs.guzzlephp.org/en/stable/request-options.htm
     *
     * @see https://docs.guzzlephp.org/en/stable/request-options.htm
     */
    public function __construct(
        public readonly string $method,
        public readonly string $url,
        public readonly array $options = [],
    ) {
    }
}
