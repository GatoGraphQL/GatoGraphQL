<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\Error;

use Throwable;

abstract class AbstractErrorManager implements ErrorManagerInterface
{
    public function isCMSError(mixed $thing): bool
    {
        return $thing instanceof Throwable;
    }
}
