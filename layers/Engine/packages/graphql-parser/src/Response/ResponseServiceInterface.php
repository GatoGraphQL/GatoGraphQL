<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Response;

use stdClass;

interface ResponseServiceInterface
{
    public function jsonEncodeArrayOrStdClassValue(array|stdClass $value): string;
}
