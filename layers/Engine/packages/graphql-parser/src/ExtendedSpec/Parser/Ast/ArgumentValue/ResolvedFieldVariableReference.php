<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Location;

class ResolvedFieldVariableReference extends AbstractDynamicVariableReference
{
    public function __construct(
        string $name,
        protected ?FieldInterface $field,
        Location $location,
    ) {
        parent::__construct($name, $location);
    }

    /**
     * This function will never be called!
     */
    public function getValue(): mixed
    {
        return null;
    }
}
