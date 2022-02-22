<?php

declare(strict_types=1);

namespace PoP\Engine\Response;

use stdClass;

interface OutputServiceInterface
{
    public function jsonEncodeArrayOrStdClassValue(array|stdClass $value): string;
}
