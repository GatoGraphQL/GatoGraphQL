<?php

declare(strict_types=1);

namespace PoP\Engine\ErrorHandling;

use PoP\ComponentModel\ErrorHandling\Error;

interface ErrorManagerInterface
{
    public function convertFromCMSToPoPError(mixed $cmsError): Error;
    public function isCMSError(mixed $thing): bool;
}
