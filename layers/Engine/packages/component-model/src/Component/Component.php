<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Component;

final class Component
{
    /**
     * @param array<string,mixed> $atts
     */
    public function __construct(
        public readonly string $processorClass,
        public readonly string $name,
        public readonly array $atts = [],
    ) {
    }

    public function __toString(): string
    {
        return sprintf(
            '[%s, %s%s]',
            $this->processorClass,
            $this->name,
            $this->atts !== [] ? ', ' . json_encode($this->atts) : ''
        );
    }
}
