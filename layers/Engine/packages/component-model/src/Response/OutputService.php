<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Response;

use PoP\Root\Services\BasicServiceTrait;
use stdClass;

class OutputService implements OutputServiceInterface
{
    use BasicServiceTrait;

    /**
     * Encode the array, and trim to 500 chars max
     *
     * @param mixed[]|stdClass $value
     */
    public function jsonEncodeArrayOrStdClassValue(array|stdClass $value): string
    {
        return mb_strimwidth(
            (string)json_encode($value),
            0,
            500,
            $this->__('...', 'graphql-parser')
        );
    }
}
