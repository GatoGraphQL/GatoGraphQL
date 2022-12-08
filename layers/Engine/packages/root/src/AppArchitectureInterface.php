<?php

declare(strict_types=1);

namespace PoP\Root;

interface AppArchitectureInterface
{
    public static function isDowngraded(): bool;
}
