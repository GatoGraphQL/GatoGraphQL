<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Error;

interface GraphQLErrorMessageProviderInterface
{
    public function getDuplicateKeyInInputObjectSyntaxErrorMessage(string $key): string;

    public function getInvalidStringUnicodeEscapeSequenceErrorMessage(string $codepoint): string;

    public function getUnexpectedStringEscapedCharacterErrorMessage(string $ch): string;

    public function getCantRecognizeTokenTypeErrorMessage(): string;

    public function getUnexpectedTokenErrorMessage(string $tokenName): string;

    public function getContextNotSetErrorMessage(string $variableName): string;
}
