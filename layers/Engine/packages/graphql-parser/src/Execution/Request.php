<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Execution;

use PoP\Translation\Services\WithTranslationAPITrait;
use PoPBackbone\GraphQLParser\Execution\Request as UpstreamRequest;

class Request extends UpstreamRequest
{
    use WithTranslationAPITrait;

    protected function getVariableHasntBeenDeclaredErrorMessage(string $variableName): string
    {
        return $this->translationAPI->__(\sprintf('Variable \'%s\' hasn\'t been declared', $variableName), 'graphql-parser');
    }
}
