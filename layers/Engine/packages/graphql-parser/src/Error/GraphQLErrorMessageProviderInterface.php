<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Error;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;

interface GraphQLErrorMessageProviderInterface
{
    public function getAffectedDirectivesReferencedMoreThanOnceErrorMessage(
        Directive $directive,
    ): string;

    public function getAffectedDirectivesUnderPosNotEmptyErrorMessage(
        Directive $directive,
        Argument $argument
    ): string;

    public function getAffectedDirectivesUnderPosNotPositiveIntErrorMessage(
        Directive $directive,
        Argument $argument,
        mixed $itemValue
    ): string;

    public function getNoAffectedDirectiveUnderPosErrorMessage(
        Directive $directive,
        Argument $argument,
        int $itemValue
    ): string;

    public function getNoOperationMatchesNameErrorMessage(string $operationName): string;

    public function getNoOperationNameProvidedErrorMessage(): string;

    public function getVariableNotSubmittedErrorMessage(string $variableName): string;

    public function getExecuteValidationErrorMessage(string $methodName): string;

    public function getIncorrectRequestSyntaxErrorMessage(?string $syntax): string;

    public function getCantParseArgumentErrorMessage(): string;

    public function getDuplicateKeyInInputObjectSyntaxErrorMessage(string $key): string;

    public function getInvalidStringUnicodeEscapeSequenceErrorMessage(string $codepoint): string;

    public function getUnexpectedStringEscapedCharacterErrorMessage(string $ch): string;

    public function getUnexpectedTokenErrorMessage(string $tokenName): string;

    public function getNoOperationsDefinedInQueryErrorMessage(): string;

    public function getDuplicateOperationNameErrorMessage(string $operationName): string;

    public function getEmptyOperationNameErrorMessage(): string;

    public function getFragmentNotDefinedInQueryErrorMessage(string $fragmentName): string;

    public function getFragmentNotUsedErrorMessage(string $fragmentName): string;

    public function getDuplicateVariableNameErrorMessage(string $variableName): string;

    public function getVariableNotDefinedInOperationErrorMessage(string $variableName): string;

    public function getVariableNotUsedErrorMessage(string $variableName): string;

    public function getDuplicateArgumentErrorMessage(string $argumentName): string;

    public function getContextNotSetErrorMessage(string $variableName): string;

    public function getValueIsNotSetForVariableErrorMessage(string $variableName): string;

    public function getVariableDoesNotExistErrorMessage(string $variableReferenceName): string;
}
