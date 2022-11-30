<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

interface WithValueInterface extends AstInterface
{
    /**
     * Allow the Variable to indicate it has not been provided
     * a value, as to not inject `null` in the argument in that case,
     * which would break a non-nullable type.
     */
    public function hasValue(): bool;
    public function getValue(): mixed;
}
