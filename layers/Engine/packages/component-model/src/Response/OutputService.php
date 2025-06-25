<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Response;

use PoP\Root\Services\AbstractBasicService;
use stdClass;

class OutputService extends AbstractBasicService implements OutputServiceInterface
{
    /**
     * Encode the array, and trim to 500 chars max
     *
     * @param mixed[]|stdClass $value
     */
    public function jsonEncodeArrayOrStdClassValue(array|stdClass $value): string
    {
        return mb_strimwidth(
            str_replace(["\r", "\n"], ['\r', '\n'], (string)json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)),
            0,
            500,
            $this->__('...', 'graphql-parser')
        );
    }
}
