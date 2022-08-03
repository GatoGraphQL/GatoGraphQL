<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Execution;

use PoP\GraphQLParser\Exception\AbstractDeferredValuePromiseException;

interface ValueResolutionPromiseInterface
{
    /**
     * @throws AbstractDeferredValuePromiseException
     */
    public function resolveValue(): mixed;
}
