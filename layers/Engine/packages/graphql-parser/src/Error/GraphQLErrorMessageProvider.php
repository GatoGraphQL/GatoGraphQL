<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Error;

use PoP\Root\Services\BasicServiceTrait;

class GraphQLErrorMessageProvider implements GraphQLErrorMessageProviderInterface
{
    use BasicServiceTrait;

    public function getUnexpectedStringEscapedCharacterErrorMessage(string $ch): string
    {
        return \sprintf($this->__('Unexpected string escaped character \'%s\'', 'graphql-server'), $ch);
    }

    public function getCantRecognizeTokenTypeErrorMessage(): string
    {
        return $this->__('Can\t recognize token type', 'graphql-server');
    }

    public function getUnexpectedTokenErrorMessage(string $tokenName): string
    {
        return \sprintf($this->__('Unexpected token \'%s\'', 'graphql-server'), $tokenName);
    }

    public function getContextNotSetErrorMessage(string $variableName): string
    {
        return \sprintf($this->__('Context has not been set for variable \'%s\'', 'graphql-server'), $variableName);
    }
}
