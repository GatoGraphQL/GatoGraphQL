<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\ExtendedSpec\Execution\ObjectFieldValuePromise;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Location;

class ObjectResolvedFieldValueReference extends AbstractRuntimeVariableReference
{
    public function __construct(
        string $name,
        protected FieldInterface $field,
        Location $location,
    ) {
        parent::__construct($name, $location);
    }

    public function getValue(): mixed
    {
        return new ObjectFieldValuePromise($this->field);
    }
}
