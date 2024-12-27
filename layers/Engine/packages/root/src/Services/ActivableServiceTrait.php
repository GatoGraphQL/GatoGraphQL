<?php

declare(strict_types=1);

namespace PoP\Root\Services;

/**
 * Default implementation for services
 */
trait ActivableServiceTrait
{
    public function isServiceEnabled(): bool
    {
        return true;
    }
}
