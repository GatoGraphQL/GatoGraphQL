<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Component;

final class Component
{
    /**
     * Lazy memo for `getCacheKey()` — `serialize($this->atts)` is
     * non-trivial (atts can be a list of field unique IDs), and the
     * key is read 350K+ times per request from the various per-
     * processor cache helpers. Computing it once on the instance
     * avoids both the repeated `serialize` CPU and the repeated
     * string-allocation memory cost.
     */
    private ?string $cacheKey = null;

    /**
     * @param array<string,mixed> $atts
     */
    public function __construct(
        public readonly string $processorClass,
        public readonly string $name,
        public readonly array $atts = [],
    ) {
    }

    public function asString(): string
    {
        return sprintf(
            '[%s, %s%s]',
            $this->processorClass,
            $this->name,
            $this->atts !== [] ? ', ' . json_encode($this->atts) : ''
        );
    }

    /**
     * Stable value-key for memoization (matches `==` semantics for
     * value-equal Components). Format:
     * `processorClass | name | serialize(atts)`.
     */
    public function getCacheKey(): string
    {
        return $this->cacheKey ??= $this->processorClass . '|' . $this->name . '|' . serialize($this->atts);
    }
}
