<?php

declare(strict_types=1);

namespace PoP\Engine\ErrorHandling;

use Throwable;

abstract class AbstractErrorManager implements ErrorManagerInterface
{
    public function isCMSError(object $object): bool
    {
        return $object instanceof Throwable;
    }
}
