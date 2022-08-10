<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\ExtendedSpec\Execution\ObjectResolvedDynamicVariableValuePromise;

class ObjectResolvedDynamicVariableReference extends AbstractDynamicVariableReference
{
    public function getValue(): mixed
    {
        return new ObjectResolvedDynamicVariableValuePromise($this);
    }
}
