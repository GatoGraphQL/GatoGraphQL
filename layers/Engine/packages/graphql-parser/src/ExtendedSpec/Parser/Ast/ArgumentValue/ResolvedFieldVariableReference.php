<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\ExtendedSpec\Execution\FieldValuePromise;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Location;

class ResolvedFieldVariableReference extends AbstractDynamicVariableReference
{
    protected FieldInterface $field;

    public function __construct(
        string $name,
        FieldInterface $field,
        Location $location,
    ) {
        // Remove the directives from the field
        $this->field = new LeafField(
            $field->getName(),
            $field->getAlias(),
            $field->getArguments(),
            [],
            $location
        );
        parent::__construct($name, $location);
    }

    public function getField(): FieldInterface
    {
        return $this->field;
    }

    public function getValue(): mixed
    {
        return new FieldValuePromise($this->field);
    }
}
