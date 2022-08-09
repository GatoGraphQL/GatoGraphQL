<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\ExtendedSpec\Execution\ObjectResolvedDynamicVariableValuePromise;

class ObjectResolvedDynamicVariableReference extends AbstractRuntimeVariableReference
{
    public function getValue(): mixed
    {
        return new ObjectResolvedDynamicVariableValuePromise($this);
    }
}
