<?php

declare(strict_types=1);

namespace PoP\Engine\ErrorHandling;

interface ErrorHelperInterface
{
    public function returnResultOrConvertError(mixed $result): mixed;
}
