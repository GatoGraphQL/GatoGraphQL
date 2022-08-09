<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\ExtendedSpec\Execution\DocumentDynamicVariableValuePromise;

class DocumentDynamicVariableReference extends AbstractRuntimeVariableReference
{
    public function getValue(): mixed
    {
        return new DocumentDynamicVariableValuePromise($this);
    }
}
