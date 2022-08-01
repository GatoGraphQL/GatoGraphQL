<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\Exception\Parser\InvalidDynamicContextException;
use PoP\GraphQLParser\ExtendedSpec\Execution\DynamicVariableValuePromise;

class DynamicVariableReference extends AbstractDynamicVariableReference
{
    /**
     * @throws InvalidDynamicContextException When accessing non-declared Dynamic Variables
     */
    public function getValue(): mixed
    {
        return new DynamicVariableValuePromise($this);
    }
}
