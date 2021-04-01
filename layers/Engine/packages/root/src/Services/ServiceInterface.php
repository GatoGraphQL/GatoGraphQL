<?php

declare(strict_types=1);

namespace PoP\Root\Services;

/**
 * Container services
 */
interface ServiceInterface
{
    public function isServiceEnabled(): bool;
}
