<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReference;
use PoP\GraphQLParser\Spec\Parser\Location;

/**
 * Class that leverages the syntax of a Variable (i.e. $someVarName)
 * to provide additional functionalities, with the value
 * to be calculated on runtime and provided via a Promise.
 */
abstract class AbstractRuntimeVariableReference extends VariableReference implements RuntimeVariableReferenceInterface
{
    public function __construct(
        string $name,
        Location $location,
    ) {
        parent::__construct($name, null, $location);
    }
}
