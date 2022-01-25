<?php

declare(strict_types=1);

namespace PoP\CacheControl\Managers;

interface CacheControlEngineInterface
{
    /**
     * Add a max age from a requested field
     */
    public function addMaxAge(int $maxAge): void;

    /**
     * Calculate the request's max age as the minimum max age from all the requested fields.
     * Return an array with [key]: header name, [value]: header value
     *
     * @return array<string,string>|null
     */
    public function getCacheControlHeaders(): ?array;
}
