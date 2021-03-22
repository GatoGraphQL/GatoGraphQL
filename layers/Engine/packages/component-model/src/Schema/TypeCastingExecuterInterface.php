<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Schema;

interface TypeCastingExecuterInterface
{
    /**
     * Cast the value to the indicated type, or return null or Error (with a message) if it fails
     */
    public function cast(string $type, mixed $value): mixed;
}
