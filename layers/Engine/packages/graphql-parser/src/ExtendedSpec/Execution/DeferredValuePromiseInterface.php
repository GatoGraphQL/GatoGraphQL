<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Execution;

interface DeferredValuePromiseInterface
{
    public function resolveValue(): mixed;
}
