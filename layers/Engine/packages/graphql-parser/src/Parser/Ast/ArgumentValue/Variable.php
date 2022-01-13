<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Parser\Ast\ArgumentValue;

use PoP\Root\Services\StandaloneServiceTrait;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\Variable as UpstreamVariable;

class Variable extends UpstreamVariable
{
    use StandaloneServiceTrait;

    protected function getValueIsNotSetForVariableErrorMessage(string $variableName): string
    {
        return \sprintf(
            $this->__('Value is not set for variable \'%s\'', 'graphql-parser'),
            $variableName
        );
    }
}
