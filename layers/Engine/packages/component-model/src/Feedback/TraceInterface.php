<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Feedback;

interface TraceInterface
{
    public function getID(): string|int;
    /**
     * @return array<string,mixed>
     */
    public function getData(): array;
}
