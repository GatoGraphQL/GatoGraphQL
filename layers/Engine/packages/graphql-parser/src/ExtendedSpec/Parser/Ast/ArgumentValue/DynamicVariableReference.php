<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\Exception\Parser\InvalidDynamicContextException;
use PoP\GraphQLParser\ExtendedSpec\Execution\DynamicVariableValuePromise;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\Root\Exception\ShouldNotHappenException;

class DynamicVariableReference extends AbstractDynamicVariableReference
{
    private ?Context $context = null;

    public function setContext(?Context $context): void
    {
        $this->context = $context;
    }

    /**
     * The value for the requested variable follows this logic:
     *
     *   1. Get the value from the Context,
     *      i.e. where a "static" Variable was defined
     *   2. (If it does not exist) Get the value from the AppState,
     *      i.e. where a "dynamic" value was defined (eg: via @export)
     *   3. (If it does not exist) Return error
     *
     * @throws InvalidDynamicContextException When accessing non-declared Dynamic Variables
     */
    public function getValue(): mixed
    {
        if ($this->context === null) {
            throw new ShouldNotHappenException(
                sprintf(
                    $this->__('Context has not been set for dynamic variable reference \'%s\'', 'graphql-parser'),
                    $this->name
                )
            );
        }

        // 1. Treat the variable as "static"
        if ($this->context->hasVariableValue($this->name)) {
            return $this->context->getVariableValue($this->name);
        }

        return new DynamicVariableValuePromise($this);
    }
}
