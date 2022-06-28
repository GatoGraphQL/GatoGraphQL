<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue;

interface CoercibleArgumentValueAstInterface extends ArgumentValueAstInterface
{
    /**
     * @internal Method used by the Engine for coercing values. Don't call otherwise!
     */
    public function setValue(mixed $value): void;
}
