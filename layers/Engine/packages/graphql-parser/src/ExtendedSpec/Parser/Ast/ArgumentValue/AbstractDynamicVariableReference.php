<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReference;
use PoP\GraphQLParser\Spec\Parser\Location;

abstract class AbstractDynamicVariableReference extends VariableReference implements DynamicVariableReferenceInterface
{
    public function __construct(
        string $name,
        Location $location,
    ) {
        parent::__construct($name, null, $location);
    }
}
