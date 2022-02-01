<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Error;

interface GraphQLErrorMessageProviderInterface
{
    public function getNoOperationNameProvidedErrorMessage(): string;

    public function getExecuteValidationErrorMessage(string $methodName): string;

    public function getIncorrectRequestSyntaxErrorMessage(?string $syntax): string;

    public function getCantParseArgumentErrorMessage(): string;

    public function getDuplicateKeyInInputObjectSyntaxErrorMessage(string $key): string;

    public function getInvalidStringUnicodeEscapeSequenceErrorMessage(string $codepoint): string;

    public function getUnexpectedStringEscapedCharacterErrorMessage(string $ch): string;

    public function getCantRecognizeTokenTypeErrorMessage(): string;

    public function getUnexpectedTokenErrorMessage(string $tokenName): string;

    public function getNoOperationsDefinedInQueryErrorMessage(): string;

    public function getContextNotSetErrorMessage(string $variableName): string;
}
