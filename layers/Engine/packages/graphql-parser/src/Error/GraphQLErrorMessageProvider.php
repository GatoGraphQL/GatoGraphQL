<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Error;

use PoP\GraphQLParser\Response\OutputServiceInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\Root\Services\BasicServiceTrait;
use stdClass;

class GraphQLErrorMessageProvider implements GraphQLErrorMessageProviderInterface
{
    use BasicServiceTrait;

    private ?OutputServiceInterface $outputService = null;

    final public function setOutputService(OutputServiceInterface $outputService): void
    {
        $this->outputService = $outputService;
    }
    final protected function getOutputService(): OutputServiceInterface
    {
        return $this->outputService ??= $this->instanceManager->getInstance(OutputServiceInterface::class);
    }

    public function getAffectedDirectivesReferencedMoreThanOnceErrorMessage(
        Directive $directive,
    ): string {
        return \sprintf(
            $this->__('Meta directive \'%s\' is nesting a directive already nested by another meta-directive', 'graphql-parser'),
            $directive->getName()
        );
    }

    public function getAffectedDirectivesUnderPosNotEmptyErrorMessage(
        Directive $directive,
        Argument $argument
    ): string {
        return \sprintf(
            $this->__('Argument \'%s\' in directive \'%s\' cannot be null or empty', 'graphql-parser'),
            $argument->getName(),
            $directive->getName()
        );
    }

    public function getAffectedDirectivesUnderPosNotPositiveIntErrorMessage(
        Directive $directive,
        Argument $argument,
        mixed $itemValue
    ): string {
        return \sprintf(
            $this->__('Argument \'%s\' in directive \'%s\' must be an array of positive integers, array item \'%s\' is not allowed', 'graphql-parser'),
            $argument->getName(),
            $directive->getName(),
            is_array($itemValue) || ($itemValue instanceof stdClass)
                ? $this->getOutputService()->jsonEncodeArrayOrStdClassValue($itemValue)
                : $itemValue
        );
    }

    public function getNoAffectedDirectiveUnderPosErrorMessage(
        Directive $directive,
        Argument $argument,
        int $itemValue
    ): string {
        return \sprintf(
            $this->__('There is no directive in relative position \'%s\' from meta directive \'%s\', as indicated in argument \'%s\'', 'graphql-parser'),
            $itemValue,
            $directive->getName(),
            $argument->getName(),
        );
    }

    public function getNoOperationMatchesNameErrorMessage(string $operationName): string
    {
        return \sprintf($this->__('Operation with name \'%s\' does not exist', 'graphql-server'), $operationName);
    }

    public function getNoOperationNameProvidedErrorMessage(): string
    {
        return $this->__('The operation name must be provided', 'graphql-server');
    }

    public function getVariableNotSubmittedErrorMessage(string $variableName): string
    {
        return \sprintf($this->__('Variable \'%s\' hasn\'t been submitted', 'graphql-server'), $variableName);
    }

    public function getExecuteValidationErrorMessage(string $methodName): string
    {
        return \sprintf(
            $this->__('Before executing `%s`, must call `%s`', 'graphql-server'),
            $methodName,
            'validateAndInitialize'
        );
    }

    public function getIncorrectRequestSyntaxErrorMessage(?string $syntax): string
    {
        $errorMessage = $this->__('Incorrect request syntax', 'graphql-server');
        if ($syntax === null) {
            return $errorMessage;
        }
        return \sprintf(
            $this->__('%s: \'%s\'', 'graphql-server'),
            $errorMessage,
            $syntax
        );
    }

    public function getCantParseArgumentErrorMessage(): string
    {
        return $this->__('Can\'t parse argument', 'graphql-parser');
    }

    public function getDuplicateKeyInInputObjectSyntaxErrorMessage(string $key): string
    {
        return \sprintf($this->__('Input object has duplicate key \'%s\'', 'graphql-server'), $key);
    }

    public function getInvalidStringUnicodeEscapeSequenceErrorMessage(string $codepoint): string
    {
        return \sprintf($this->__('Invalid string unicode escape sequece \'%s\'', 'graphql-server'), $codepoint);
    }

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

    public function getNoOperationsDefinedInQueryErrorMessage(): string
    {
        return $this->__('No operations defined in the query', 'graphql-server');
    }

    public function getDuplicateOperationNameErrorMessage(string $operationName): string
    {
        return \sprintf($this->__('Operation name \'%s\' is duplicated', 'graphql-server'), $operationName);
    }

    public function getEmptyOperationNameErrorMessage(): string
    {
        return $this->__('When submitting more than 1 operation, no operation name can be empty', 'graphql-server');
    }

    public function getFragmentNotDefinedInQueryErrorMessage(string $fragmentName): string
    {
        return \sprintf($this->__('Fragment \'%s\' is not defined in query', 'graphql-server'), $fragmentName);
    }

    public function getDuplicateFragmentNameErrorMessage(string $fragmentName): string
    {
        return \sprintf($this->__('Fragment name \'%s\' is duplicated', 'graphql-server'), $fragmentName);
    }

    public function getCyclicalFragmentErrorMessage(string $fragmentName): string
    {
        return \sprintf($this->__('Fragment \'%s\' is cyclical', 'graphql-server'), $fragmentName);
    }

    public function getFragmentNotUsedErrorMessage(string $fragmentName): string
    {
        return \sprintf($this->__('Fragment \'%s\' is not used', 'graphql-server'), $fragmentName);
    }

    public function getDuplicateVariableNameErrorMessage(string $variableName): string
    {
        return \sprintf($this->__('Variable name \'%s\' is duplicated', 'graphql-server'), $variableName);
    }

    public function getVariableNotDefinedInOperationErrorMessage(string $variableName): string
    {
        return \sprintf($this->__('Variable \'%s\' has not been defined in the operation', 'graphql-server'), $variableName);
    }

    public function getVariableNotUsedErrorMessage(string $variableName): string
    {
        return \sprintf($this->__('Variable \'%s\' is not used', 'graphql-server'), $variableName);
    }

    public function getDuplicateArgumentErrorMessage(string $argumentName): string
    {
        return \sprintf($this->__('Argument \'%s\' is duplicated', 'graphql-server'), $argumentName);
    }

    public function getContextNotSetErrorMessage(string $variableName): string
    {
        return \sprintf($this->__('Context has not been set for variable \'%s\'', 'graphql-server'), $variableName);
    }

    public function getValueIsNotSetForVariableErrorMessage(string $variableName): string
    {
        return \sprintf($this->__('Value is not set for variable \'%s\'', 'graphql-server'), $variableName);
    }

    public function getVariableDoesNotExistErrorMessage(string $variableReferenceName): string
    {
        return \sprintf($this->__('No variable exists for variable reference \'%s\'', 'graphql-server'), $variableReferenceName);
    }
}
