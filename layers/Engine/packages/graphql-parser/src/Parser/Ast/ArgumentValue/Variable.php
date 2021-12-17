<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\Variable;

use PoP\BasicService\BasicServiceTrait;
use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\Variable as UpstreamVariable;

class Variable extends UpstreamVariable
{
    use BasicServiceTrait;

    protected function getValueIsNotSetForVariableErrorMessage(string $variableName): string
    {
        return \sprintf(
            $this->getTranslationAPI()->__('Value is not set for variable \'%s\'', 'graphql-parser'),
            $variableName
        );
    }
}
