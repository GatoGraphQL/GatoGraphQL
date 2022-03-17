<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue;

use PoP\GraphQLParser\Spec\Parser\Ast\WithValueInterface;
use PoP\GraphQLParser\Spec\Parser\Location;
use stdClass;

trait VariableValueAstTrait
{
    protected function convertVariableValueToAst(
        mixed $variableValue,
        Location $location
    ): WithValueInterface {
        if (is_array($variableValue)) {
            return new InputList($variableValue, $location);
        }
        if ($variableValue instanceof stdClass) {
            return new InputObject($variableValue, $location);
        }
        return new Literal($variableValue, $location);
    }
}
