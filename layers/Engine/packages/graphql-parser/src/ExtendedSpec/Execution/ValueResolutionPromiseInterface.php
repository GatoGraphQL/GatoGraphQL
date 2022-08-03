<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Execution;

use PoP\GraphQLParser\Exception\AbstractValueResolutionPromiseException;

interface ValueResolutionPromiseInterface
{
    /**
     * @throws AbstractValueResolutionPromiseException
     */
    public function resolveValue(): mixed;
}
