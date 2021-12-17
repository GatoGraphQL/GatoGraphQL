<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Execution;

use PoP\BasicService\BasicServiceTrait;
use PoPBackbone\GraphQLParser\Execution\Request as UpstreamRequest;

class Request extends UpstreamRequest
{
    use BasicServiceTrait;

    protected function getVariableHasntBeenDeclaredErrorMessage(string $variableName): string
    {
        return $this->getTranslationAPI()->__(\sprintf('Variable \'%s\' hasn\'t been declared', $variableName), 'graphql-parser');
    }
}
