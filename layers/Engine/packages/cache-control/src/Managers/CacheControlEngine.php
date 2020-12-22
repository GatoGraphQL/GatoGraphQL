<?php

declare(strict_types=1);

namespace PoP\CacheControl\Managers;

class CacheControlEngine implements CacheControlEngineInterface
{
    protected ?int $minimumMaxAge = null;

    /**
     * Add a max age from a requested field
     *
     * @param integer $maxAge
     * @return void
     */
    public function addMaxAge(int $maxAge): void
    {
        // Cache only GET operations
        if ($_SERVER['REQUEST_METHOD'] != 'GET') {
            return;
        }
        // Keep the minumum max age
        if (is_null($this->minimumMaxAge) || $maxAge < $this->minimumMaxAge) {
            $this->minimumMaxAge = $maxAge;
        }
    }

    /**
     * Calculate the request's max age as the minimum max age from all the requested fields
     *
     * @param integer $maxAge
     * @return void
     */
    public function getCacheControlHeader(): ?string
    {
        if (!is_null($this->minimumMaxAge)) {
            // If the minimum age is 0, send the "do not cache" instruction
            if ($this->minimumMaxAge === 0) {
                return 'Cache-Control: no-store';
            }
            return sprintf(
                'Cache-Control: max-age=%s',
                $this->minimumMaxAge
            );
        }
        // No field was requested, return no header
        return null;
    }
}
