<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Component;

final class Component
{
    function __construct(
        public readonly string $class,
        public readonly string $name,
        public readonly array $atts = [],
    ) {        
    }
}
