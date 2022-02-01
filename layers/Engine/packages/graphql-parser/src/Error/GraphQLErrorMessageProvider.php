<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Error;

use PoP\Root\Services\BasicServiceTrait;

class GraphQLErrorMessageProvider implements GraphQLErrorMessageProviderInterface
{
    use BasicServiceTrait;

    public function getContextNotSetErrorMessage(string $variableName): string
    {
        return \sprintf($this->__('Context has not been set for variable \'%s\'', 'graphql-server'), $variableName);
    }
}
