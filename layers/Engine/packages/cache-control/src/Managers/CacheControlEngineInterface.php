<?php

declare(strict_types=1);

namespace PoP\CacheControl\Managers;

interface CacheControlEngineInterface
{
    /**
     * Add a max age from a requested field
     *
     * @param integer $maxAge
     * @return void
     */
    public function addMaxAge(int $maxAge): void;
    /**
     * Calculate the request's max age as the minimum max age from all the requested fields
     *
     * @param integer $maxAge
     * @return void
     */
    public function getCacheControlHeader(): ?string;
}
