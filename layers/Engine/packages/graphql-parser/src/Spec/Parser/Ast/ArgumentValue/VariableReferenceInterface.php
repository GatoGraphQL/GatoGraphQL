<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue;

interface VariableReferenceInterface extends ArgumentValueAstInterface
{
    /**
     * Indicate if a field equals another one based on its properties,
     * not on its object hash ID.
     */
    public function equalsTo(VariableReference $variableReference): bool;
}
