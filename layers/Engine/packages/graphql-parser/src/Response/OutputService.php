<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Response;

use PoP\BasicService\BasicServiceTrait;
use stdClass;

class OutputService implements OutputServiceInterface
{
    use BasicServiceTrait;

    /**
     * Encode the array, and trim to 500 chars max
     *
     * @param mixed[] $value
     */
    public function jsonEncodeArrayOrStdClassValue(array|stdClass $value): string
    {
        return mb_strimwidth(
            json_encode($value),
            0,
            500,
            $this->__('...', 'graphql-parser')
        );
    }
}
