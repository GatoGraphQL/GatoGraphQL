<?php

declare(strict_types=1);

namespace PoP\Root\Services;

/**
 * Default implementation for services
 */
trait ServiceTrait
{
    public function isServiceEnabled(): bool
    {
        return true;
    }
}
