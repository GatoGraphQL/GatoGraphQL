<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Execution;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class FieldValuePromise
{
    public function __construct(
        public readonly FieldInterface $field,
    ) {
    }
}
