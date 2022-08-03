<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Execution;

use PoP\GraphQLParser\Exception\DeferredValuePromiseExceptionInterface;

interface DeferredValuePromiseInterface
{
    /**
     * @throws DeferredValuePromiseExceptionInterface
     */
    public function resolveValue(): mixed;
}
