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
    
    /**
     * Indicate if the promise must be resolved on the object
     * being iterated upon during the field resolution or not.
     *
     * The field/directiveArgs containing the promise must then be resolved:
     *
     *   - `true`: object by object
     *   - `false`: only once during the Engine Iteration
     *              for all involved fields/objects
     */
    public function mustResolveOnObject(): bool;
}
