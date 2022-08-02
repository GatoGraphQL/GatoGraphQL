<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\ExtendedSpec\Execution\DynamicVariableValuePromise;

class DynamicVariableReference extends AbstractRuntimeVariableReference
{
    public function getValue(): mixed
    {
        return new DynamicVariableValuePromise($this);
    }
}
